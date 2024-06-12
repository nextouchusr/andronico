<?php

declare(strict_types=1);

namespace Nextouch\GestLogis\Model;

use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Exception;


class Import
{
    const TIMEOUT_SLOT = 100;
    const CSV_FILE_PATH = '/var/gestlogis/import/';
    const CSV_BACKUP_FILE_PATH = '/var/gestlogis/import/backup/';
    const CSV_FILE_NAME = 'gestlogis.csv';
    const CSV_DATA_SEPRATOR = ';';

    private $_resource;
    private $_file;
    private $_dir;
    private $_headers;

    private $_connection;

    public function __construct(
        ResourceConnection $resource,
        File $file,
        DirectoryList $dir
    ) {
        $this->_connection = $resource->getConnection();
        $this->_file = $file;
        $this->_dir = $dir;

    }

    /**
     *
     * @return void
     */
    public function execute()
    {
        try {
            if ($this->fileExists()) {
                $datas = $this->readFile();
                if ($datas) {

                    $this->_headers = $datas[0];
                    array_shift($datas);

                    // Manage Services 
                    $this->manageServices($this->_headers);

                    // Manage Postcodes
                    $postcodes = $this->getPostcodes($datas);
                    $this->managePostCodes($postcodes);

                    // Manage Attributes
                    $attributes = $this->getAttributes($datas);
                    $this->managetAttributes($attributes);

                    // Manage Relationship
                    $query = "TRUNCATE TABLE  nextouch_gestlogis_postcode_service_attributes";
                    $this->_connection->query($query);

                    //$i=0;
                    foreach ($datas as $_data) {
                       // echo "ROW ".$i."\n";
                        $this->manageRelationship($_data);
                       // $i++;
                    }
                }
                $this->backupFile();
                return;
            }
            throw new Exception('File Not Exists. Put your file on ${mage_root}' . self::CSV_FILE_PATH);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function manageRelationship($row)
    {
        $attributeSetId = 0;
        $attributeIds = [];
        $postcodeIds = [];

        $query = "SELECT attribute_set_id FROM eav_attribute_set WHERE attribute_set_name = '" . $row[0] . "'";
        $result = $this->_connection->fetchRow($query);

        if (isset($result['attribute_set_id'])) {
            $attributeSetId = (int) $result['attribute_set_id'];

            $attributes = $this->convertAttributes($row);

            if (count($attributes)) {
                foreach ($attributes as $attribute) {
                    $query = "SELECT entity_id FROM nextouch_gestlogis_attributes WHERE attribute_code = '" . $attribute['key'] . "' and attribute_value = '" . $attribute['value'] . "'";
                    $result = $this->_connection->fetchRow($query);
                    if ($result) {
                        $attributeIds[] = (int) $result['entity_id'];
                    }
                }
            }

            $postcodes = $this->convertPostcodes($row);
            if (count($postcodes)) {
                foreach ($postcodes as $postcode) {
                    $query = "SELECT entity_id FROM nextouch_gestlogis_shipping_postcode WHERE postcode = '" . $postcode . "'";
                    $result = $this->_connection->fetchRow($query);
                    if ($result) {
                        $postcodeIds[] = (int) $result['entity_id'];
                    }
                }
            }

            if (count($postcodeIds) && $attributeSetId > 0) {
                $insertRecords = [];
                //$start = microtime(true);
                foreach ($row as $key => $value) {
                    if ($key > 3) {
                        $_insertRecords = $this->managePostcodeService($row, $this->_headers[$key], $value, $postcodeIds, $attributeSetId, $attributeIds);
                        $insertRecords = array_merge($insertRecords, $_insertRecords);
                    }
                }
                //echo "TOTAL FOR ROW ".count($insertRecords)."\n";
                try{
                    if(count($insertRecords)) {
                        $this->_connection->insertOnDuplicate('nextouch_gestlogis_postcode_service_attributes', $insertRecords);
                    } else {
                        file_put_contents(BP . '/var/log/insertRecords.log', print_r([
                            'attributeIds' => $attributeIds,
                            'postcodeIds' => $postcodeIds,
                            'attributeSetId' => $attributeSetId
                        ], true)."\n", FILE_APPEND);			
                    }
                } catch (\Exception $e) {
                    file_put_contents(BP . '/var/log/insertRecords.log', print_r($e->getMessage(), true)."\n", FILE_APPEND);			
                }
                //$time_elapsed_secs = microtime(true) - $start;
                //echo "TOTAL TIME FOR ROW SECONDS ".$time_elapsed_secs."\n";
            }
        }

    }

    private function managePostcodeService($row, $serviceName, $servicePrice, $postcodeIds, $attributeSetId, $attributeIds)
    {
        $serviceName = $this->removeSpecialChar($serviceName);
        $code = $this->getServiceCode($serviceName);

        $postcodePrice = $row[4];

        if ($servicePrice == null) {
            $servicePrice = 0;
        }

        $requiredServices = $row[3];

        $requiredServices = explode(",", $requiredServices);
        $_requiredServices = [];
        foreach ($requiredServices as $requiredService) {
            $_value = $this->removeSpecialChar($requiredService);
            $_code = $this->getServiceCode($requiredService);
            $_requiredServices[$_code] = $_value;
        }

        $isRequiredService = isset($_requiredServices[$code]) ? 1 : 0;


        $query = "SELECT entity_id from nextouch_gestlogis_shipping_services where service_code = '" . $code . "'";
        $result = $this->_connection->fetchRow($query);
        $insertRecords= [];

        if ($result) {

            $serviceId = $result['entity_id'];
            //$startp = microtime(true);
            foreach ($postcodeIds as $postcodeId) {
                if (count($attributeIds)) {
                    foreach ($attributeIds as $attributeId) {

                        //$starts = microtime(true);
                        /*$query = "SELECT entity_id from nextouch_gestlogis_postcode_service_attributes where postcode_id = " . $postcodeId . " and service_id = " . $serviceId . " and attribute_set_id = " . $attributeSetId . " and attribute_id = " . $attributeId." limit 1";
                        $result = $this->_connection->fetchRow($query);
                        $time_elapsed_secss = microtime(true) - $starts;
                        //echo "POSTCODE SELECT SECONDS ".$time_elapsed_secss."\n";

                        if ($result) {

                            $startu = microtime(true);
                            $query = "Update nextouch_gestlogis_postcode_service_attributes Set postcode_price = '" . $postcodePrice . "', service_price = '" . $servicePrice . "' where entity_id = " . $result['entity_id'];
                            $this->_connection->query($query);
                            $time_elapsed_secsu = microtime(true) - $startu;
                            echo "POSTCODE UPDATE SECONDS ".$time_elapsed_secsu."\n";

                        } else {*/

                            //$query = "INSERT INTO nextouch_gestlogis_postcode_service_attributes (`postcode_id`, `service_id`, `attribute_set_id`, `attribute_id`, `postcode_price`, `service_price`, `is_service_required`) VALUES (" . $postcodeId . "," . $serviceId . "," . $attributeSetId . "," . $attributeId . ",'" . $postcodePrice . "','" . $servicePrice . "','" . $isRequiredService . "')";

                            $insertRecords[] = [
                                'postcode_id' => $postcodeId,
                                'service_id' => $serviceId,
                                'attribute_set_id' => $attributeSetId,
                                'attribute_id' => $attributeId,
                                'postcode_price' => $postcodePrice,
                                'service_price' => $servicePrice,
                                'is_service_required' => $isRequiredService
                            ];

                            //$this->_connection->query($query);
                        //}

                    }
                } else {
                    //$query = "INSERT INTO nextouch_gestlogis_postcode_service_attributes (`postcode_id`, `service_id`, `attribute_set_id`, `postcode_price`, `service_price`, `is_service_required`) VALUES (" . $postcodeId . "," . $serviceId . "," . $attributeSetId . ",'" . $postcodePrice . "','" . $servicePrice . "','" . $isRequiredService . "')";
                    $insertRecords[] = [
                        'postcode_id' => $postcodeId,
                        'service_id' => $serviceId,
                        'attribute_set_id' => $attributeSetId,
                        'postcode_price' => $postcodePrice,
                        'service_price' => $servicePrice,
                        'is_service_required' => $isRequiredService
                    ];
                    //$this->_connection->query($query);
                }
            }
            //$time_elapsed_secsp = microtime(true) - $startp;
            //echo "POSTCODE SECONDS ".$time_elapsed_secsp."\n";

            //exit;
            //$this->_connection->insertOnDuplicate('nextouch_gestlogis_postcode_service_attributes', $insertRecords);

        }
        return $insertRecords;
    }

    private function managetAttributes($attributes)
    {
        foreach ($attributes as $attribute) {
            $query = "SELECT * from nextouch_gestlogis_attributes where attribute_code = '" . $attribute['key'] . "' and attribute_value = '" . $attribute['value'] . "'";
            $result = $this->_connection->fetchAll($query);
            if (!count($result)) {
                $query = "INSERT INTO nextouch_gestlogis_attributes (`attribute_code`, `attribute_value`) VALUES ('" . $attribute['key'] . "','" . $attribute['value'] . "')";
                $this->_connection->query($query);
            }
        }
    }

    private function convertAttributes($row)
    {
        $attributes = [];
        if ($row[1] && $row[1] != null) {
            $rowAttributes = explode("|", $row[1]);
            foreach ($rowAttributes as $rowAttribute) {

                $attribute = explode("=", $rowAttribute);

                $attributes[] = [
                    'key' => $attribute[0],
                    'value' => $attribute[1]
                ];
            }
        }
        return $attributes;
    }

    private function getAttributes($rows)
    {
        $attributes = [];
        foreach ($rows as $row) {
            $attributes = array_merge($attributes, $this->convertAttributes($row));
        }
        return $attributes;
    }

    private function convertPostcodes($row)
    {
        $postcodes = [];
        $rowPostCodes = explode(",", $row[2]);
        foreach ($rowPostCodes as $postcode) {

            // Wild Postcode manage
            $pos = strpos($postcode, "x");
            if ($pos !== false) {
                $wpostcodes = $this->getWildcardPostcodes($postcode);
                foreach ($wpostcodes as $wpostcode) {
                    if (is_numeric($wpostcode)) {
                        $postcodes[$wpostcode] = $wpostcode;
                    }
                }
            } else if (is_numeric($postcode)) {
                $postcodes[$postcode] = $postcode;
            }
        }
        return $postcodes;
    }

    private function getPostcodes($rows)
    {
        $postcodes = [];
        foreach ($rows as $row) {
            $postcodes = array_unique(array_merge($postcodes, array_values($this->convertPostcodes($row))));
        }
        return $postcodes;
    }

    private function managePostCodes($postcodes)
    {
        foreach ($postcodes as $postcode) {
            $this->updatePostcode($postcode);
        }
    }

    private function updatePostcode($postcode)
    {
        $query = "SELECT * from nextouch_gestlogis_shipping_postcode where postcode = '" . $postcode . "'";
        $result = $this->_connection->fetchAll($query);
        if (!count($result)) {
            $query = "INSERT INTO nextouch_gestlogis_shipping_postcode (`postcode`) VALUES ('$postcode')";
            $this->_connection->query($query);
        }
    }

    private function getWildcardPostcodes($wpostcode)
    {
        $postcodes = [];
        $startPoint = str_replace("x", "0", $wpostcode);
        $endPoint = str_replace("x", "9", $wpostcode);

        for ($i = $startPoint; $i <= $endPoint; $i++) {
            $postcodes[] = (int) $i;
        }

        return $postcodes;
    }

    private function removeSpecialChar($title)
    {
        return str_ireplace(
            array(
                '\'',
                '"',
                ',',
                ';',
                '<',
                '>'
            ), ' ', $title);
    }

    private function getServiceCode($title)
    {
        $code = strtolower($title);
        return str_replace(" ", "_", $code);
    }

    private function manageServices($columns)
    {

        foreach ($columns as $key => $value) {

            if ($key > 4) {
                $value = $this->removeSpecialChar($value);
                $code = $this->getServiceCode($value);

                $query = "SELECT * from nextouch_gestlogis_shipping_services where service_code = '" . $code . "'";
                $result = $this->_connection->fetchAll($query);

                if (!count($result)) {
                    $query = "INSERT INTO nextouch_gestlogis_shipping_services (`service_code`, `service_name`) VALUES ('$code','$value')";
                    $this->_connection->query($query);
                }
            }
        }
    }

    private function readFile()
    {
        $mageRootPath = $this->_dir->getRoot();
        $file = $mageRootPath . self::CSV_FILE_PATH . self::CSV_FILE_NAME;
        if (($handle = $this->_file->fileOpen($file, 'r')) !== FALSE) {
            while ($content = $this->_file->fileGetCsv($handle)) {
                $array[] = $content;
            }

            fclose($handle);
        } else
            die("Unable to open file " . $file);
        return $array;
    }

    private function fileExists()
    {
        $mageRootPath = $this->_dir->getRoot();
        $file = $mageRootPath . self::CSV_FILE_PATH . self::CSV_FILE_NAME;
        if (file_exists($file)) {
            return true;
        }
        return false;
    }

    private function backupFile()
    {
        $mageRootPath = $this->_dir->getRoot();

        $sourceFile = $mageRootPath . self::CSV_FILE_PATH . self::CSV_FILE_NAME;
        $destimationFilePath = $mageRootPath . self::CSV_BACKUP_FILE_PATH;

        if (!$this->_file->isExists($destimationFilePath)) {
            $this->_file->createDirectory($destimationFilePath, $permissions = 0777);
        }

        $destimationFile = $mageRootPath . self::CSV_BACKUP_FILE_PATH . self::CSV_FILE_NAME . '-' . date('Y-m-d H:i:s');
        $this->_file->copy($sourceFile, $destimationFile);
        $this->_file->deleteFile($sourceFile);
        return;
    }
}

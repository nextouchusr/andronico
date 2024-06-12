<?php

declare(strict_types = 1);

namespace Nextouch\ProductAttributeEAN\Model;

use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class Import
{
    const TIMEOUT_SLOT = 100;
    const CSV_FILE_PATH = '/var/import/';
    const CSV_BACKUP_FILE_PATH = '/var/import/backup-ean/';
    const CSV_FILE_NAME = 'ean.csv';
    const CSV_DATA_SEPRATOR = ';';
    const SKU_PREFIX = 'AT-';

    /**
     * @var File
     */
    private $_file;

    /**
     * @var DirectoryList
     */
    private $_dir;

    /**
     * @var ProductCollectionFactory
     */
    private $_productCollectionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $_productRepository;

    /**
     * @param File $file
     * @param DirectoryList $dir
     * @param ProductCollectionFactory $productCollectionFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        File $file,
        DirectoryList $dir,
        ProductCollectionFactory $productCollectionFactory,
        ProductRepositoryInterface $productRepository
    ){
        $this->_file = $file;
        $this->_dir = $dir;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productRepository = $productRepository;
    }

    /**
     *
     * @return void
     */
    public function execute()
    {
        try{
            if($this->fileExists()){
                $data = $this->readFile();
                if($data){
                    $formattedData = $this->convertToFormatted($data);
                    $sleep = 0;
                    foreach($formattedData as $value){

                        // Remove EAN Value
                        if($value['remove']){
                            $sleep += 1;
                            $this->removeAttribute($value);
                        }

                        // Update EAN Value
                        if($value['update']){
                            $sleep += 1;
                            $this->updateAttribute($value);
                        }

                        // Go to Sleep
                        if($sleep == self::TIMEOUT_SLOT){
                            sleep(5);
                        }
                    }
                }
                $this->backupFile();
                return;
            }
            file_put_contents(BP . '/var/log/Error_ProductAttributeEAN.log', print_r([
                'error' => 'File Not Exists. Put your file on ${mage_root}'.self::CSV_FILE_PATH
            ], true)."\n", FILE_APPEND);
        }catch(\Exception $error){
            file_put_contents(BP . '/var/log/Error_ProductAttributeEAN.log', print_r([
                'error' => $error->getMessage()
            ], true)."\n", FILE_APPEND);
        }
    }

    private function backupFile()
    {
        $mageRootPath =  $this->_dir->getRoot();

        $sourceFile = $mageRootPath.self::CSV_FILE_PATH.self::CSV_FILE_NAME;
        $destimationFilePath = $mageRootPath.self::CSV_BACKUP_FILE_PATH;

        if(!$this->_file->isExists($destimationFilePath)){
            $this->_file->createDirectory($destimationFilePath, $permissions = 0777);
        }

        $destimationFile = $mageRootPath.self::CSV_BACKUP_FILE_PATH.self::CSV_FILE_NAME.'-'.date('Y-m-d H:i:s');
        $this->_file->copy($sourceFile, $destimationFile);
        $this->_file->deleteFile($sourceFile);
        return;
    }

    private function removeAttribute($row)
    {
        try{
            $collection = $this->_productCollectionFactory->create();
            $collection->addAttributeToFilter('sku', ['eq' => $row['sku']]);
            $collection->addAttributeToSelect(['sku']);
            $collection->setPageSize(1);
            $collection->setCurPage(1);

            
            if(count($collection)){

                $product = $this->_productRepository->get($row['sku']);
                $productEan = $product->getEan();

                if($productEan){
                    $eanAttributes = explode(",", $productEan);

                    foreach($row['remove'] as $_row){
                        if(($key = array_search($_row, $eanAttributes)) !== false) {
                            unset($eanAttributes[$key]);
                        }
                        array_values($eanAttributes);
                    }

                    $product->setEan(implode(',', $eanAttributes));
                    $this->_productRepository->save($product);
                }
            }

        }catch(\Exception $error){
            file_put_contents(BP . '/var/log/Error_ProductAttributeEAN.log', print_r([
                'row' => $row,
                'error' => $error->getMessage()
            ], true)."\n", FILE_APPEND);
        }
    }

    private function updateAttribute($row)
    {
        try{
            $collection = $this->_productCollectionFactory->create();
            $collection->addAttributeToFilter('sku', ['eq' => $row['sku']]);
            $collection->addAttributeToSelect(['sku']);
            $collection->setPageSize(1);
            $collection->setCurPage(1);

            
            if(count($collection)){

                $product = $this->_productRepository->get($row['sku']);
                $productEan = $product->getEan();

                $eanAttributes = [];
                if($productEan){
                    $eanAttributes = explode(",", $productEan);
                }

                $eanAttributes = array_unique(array_merge($eanAttributes, $row['update']));

                // Not Allowed more then 3
                $eanAttributes = array_slice($eanAttributes, 0, 3);

                $product->setEan(implode(',', $eanAttributes));
                $this->_productRepository->save($product);
            }

        }catch(\Exception $error){
            file_put_contents(BP . '/var/log/Error_ProductAttributeEAN.log', print_r([
                'row' => $row,
                'error' => $error->getMessage()
            ], true)."\n", FILE_APPEND);
        }
    }

    private function convertToFormatted($data)
    {
        $response = [];
        foreach($data as $key => $value){
            if($key > 0 && isset($value[0])){
                $explodeValue = explode(self::CSV_DATA_SEPRATOR, $value[0]);

                $sku = $explodeValue[0];   
                
                $removedEan = [];
                $updatedEan = [];
                if(isset($response[$sku])){
                    $updatedEan = $response[$sku]['update'];
                    $removedEan = $response[$sku]['remove'];
                }

                if($explodeValue[2] == "True"){
                    $updatedEan[] = $explodeValue[1];
                }

                if($explodeValue[4] == "True"){
                    $removedEan[] = $explodeValue[1];
                }
                
                $response[$sku] =[
                    "sku" => self::SKU_PREFIX . $explodeValue[0],
                    "update" => $updatedEan,
                    "remove" => $removedEan
                ];
            }
        }

        return array_values($response);

    }

    private function readFile()
    {
        $mageRootPath =  $this->_dir->getRoot();
        $file = $mageRootPath.self::CSV_FILE_PATH.self::CSV_FILE_NAME;
        if (($handle = $this->_file->fileOpen($file, 'r')) !== FALSE) {
            while($content = $this->_file->fileGetCsv($handle)){
                $array[] = $content;
            }
            
            fclose($handle);
        }else die ("Unable to open file " . $file);
        return $array;
    }

    private function fileExists()
    {
        $mageRootPath =  $this->_dir->getRoot();
        $file = $mageRootPath.self::CSV_FILE_PATH.self::CSV_FILE_NAME;
        if(file_exists($file)){
            return true;
        }
        return false;
    }
}
<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\Io\IoInterface;
use Magento\ScheduledImportExport\Model\ResourceModel\Scheduled\Operation\CollectionFactory;
use Magento\ScheduledImportExport\Model\Scheduled\Operation;
use Nextouch\ImportExport\Api\EntityDataOperationInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Psr\Log\LoggerInterface;

class WinsScheduledMagentoOperation implements EntityDataOperationInterface
{
    private string $filename;
    private string $operationName;
    private IoInterface $client;
    private ImportExportConfig $config;
    private CollectionFactory $collectionFactory;
    private LoggerInterface $logger;

    public function __construct(
        string $filename,
        string $operationName,
        IoInterface $client,
        ImportExportConfig $config,
        CollectionFactory $collectionFactory,
        LoggerInterface $logger
    ) {
        $this->filename = $filename;
        $this->operationName = $operationName;
        $this->client = $client;
        $this->config = $config;
        $this->collectionFactory = $collectionFactory;
        $this->logger = $logger;
    }

    public function run(): void
    {
        $multipleFiles = $this->searchMultipleFiles();
        if ($multipleFiles) {
            if (is_array($multipleFiles)) {
                foreach ($multipleFiles as $filename) {
                    $this->filename = $filename;
                    $this->runOperationFunction();
                }                
            }
        } else {
            // Old precedure
            $this->runOperationFunction();
        }        
    }

    private function runOperationFunction(){

        $this->logger->info(__('Starting to import Wins entity data from "%1"', $this->filename));
        
        try {

            // Forzo aggiornamento file_name dentro operations per importazione multipla
            $operation = $this->fetchOperation();
            $file_info = $operation->getData("file_info");
            $file_info["file_name"] = $this->filename;
            $operation->setData("file_info",$file_info);
            //var_dump($file_info);

            /*
            var_dump( get_class($operation));
            var_dump( get_class_methods($operation));
            var_dump( $operation->getData()); 
            */

            //die();
            $operation->run();

            $this->moveFilenameToProcessed();
        } catch (LocalizedException $e) {
            $this->moveFilenameToUnprocessed();
            $this->logger->error($e->getMessage());
        } finally {
            $this->logger->info(__('Finishing to import Wins entity data from "%1"', $this->filename));
        }
        
    }


    private function searchMultipleFiles() {

        if ( $this->filename == "prodotti.csv" || $this->filename == "giacenze.csv") {

            $config = $this->config->getWinsConfig();
            $this->client->open($config);
            $winsLocation = $this->config->getWinsLocation();
            $this->client->cd($winsLocation);
            $files = $this->client->ls();
            $this->client->close();

            $search_regex_pattern = "/".str_replace(".csv","_\d+\.csv",$this->filename)."/"; // example /prodotti_\d+\.csv/
            //echo "\r\nSearch pattern: ". $search_regex_pattern;
            $this->logger->info(__('Search pattern ' . $search_regex_pattern));

            $files_to_import = array();
            foreach ($files as $file) {
                $filename = $file['text'];
                if (preg_match($search_regex_pattern, $filename)) {
                    //echo "\r\n" . $filename;
                    $files_to_import[] = $filename;
                    $this->logger->info('File to import: ' . $filename);
                }
            }
            sort($files_to_import);
            
            //var_dump($files_to_import);
            
            return $files_to_import;

        }

        return false;
    }

    /**
     * @throws NoSuchEntityException
     */
    private function fetchOperation(): Operation
    {
        /** @var Operation $operation */
        $operation = $this->collectionFactory
            ->create()
            ->addFieldToFilter('name', $this->operationName)
            ->getFirstItem();

        if (!$operation->getId()) {
            throw new NoSuchEntityException(__('The Magento operation %1 does not exist.', $this->operationName));
        }

        return $operation;
    }

    private function moveFilenameToProcessed(): void
    {
        $dest = $this->config->getWinsProcessedFilePath($this->filename);
        $this->moveFilenameTo($dest);
    }

    private function moveFilenameToUnprocessed(): void
    {
        $dest = $this->config->getWinsUnprocessedFilePath($this->filename);
        $this->moveFilenameTo($dest);
    }

    private function moveFilenameTo(string $dest): void
    {
        $config = $this->config->getWinsConfig();
        $this->client->open($config);

        $src = $this->config->getWinsFilePath($this->filename);
        $this->client->mv($src, $dest);

        $this->client->close();
    }
}

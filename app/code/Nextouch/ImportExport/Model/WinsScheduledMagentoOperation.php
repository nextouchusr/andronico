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
    private const PROCESSED_DIR = './processed';
    private const UNPROCESSED_DIR = './unprocessed';

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
        $this->logger->info(__('Starting to import Wins entity data from "%1"', $this->filename));

        try {
            $operation = $this->fetchOperation();
            $operation->run();

            $this->moveFilenameToProcessed();
        } catch (LocalizedException $e) {
            $this->moveFilenameToUnprocessed();
            $this->logger->error($e->getMessage());
        } finally {
            $this->logger->info(__('Finishing to import Wins entity data from "%1"', $this->filename));
        }
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

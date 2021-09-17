<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Io\IoInterface;
use Nextouch\ImportExport\Api\EntityDataImportInterface;
use Nextouch\ImportExport\Api\EntityDataOperationInterface;
use Nextouch\ImportExport\Api\EntityDataProviderInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Psr\Log\LoggerInterface;

class WinsEntityDataOperation implements EntityDataOperationInterface
{
    private const PROCESSED_DIR = './processed';
    private const UNPROCESSED_DIR = './unprocessed';

    private string $filename;
    private IoInterface $client;
    private ImportExportConfig $config;
    private EntityDataProviderInterface $entityDataProvider;
    private EntityDataImportInterface $entityDataImport;
    private LoggerInterface $logger;

    public function __construct(
        string $filename,
        IoInterface $client,
        ImportExportConfig $config,
        EntityDataProviderInterface $entityDataProvider,
        EntityDataImportInterface $entityDataImport,
        LoggerInterface $logger
    ) {
        $this->filename = $filename;
        $this->client = $client;
        $this->config = $config;
        $this->entityDataProvider = $entityDataProvider;
        $this->entityDataImport = $entityDataImport;
        $this->logger = $logger;
    }

    public function run(): void
    {
        $this->logger->info(__('Starting to import Wins entity data from "%1"', $this->filename));

        try {
            $data = $this->entityDataProvider->fetchData();
            $this->entityDataImport->importData($data);

            $this->moveFilenameToProcessed();
        } catch (LocalizedException $e) {
            $this->moveFilenameToUnprocessed();
            $this->logger->error($e->getMessage());
        } finally {
            $this->logger->info(__('Finishing to import Wins entity data from "%1"', $this->filename));
        }
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

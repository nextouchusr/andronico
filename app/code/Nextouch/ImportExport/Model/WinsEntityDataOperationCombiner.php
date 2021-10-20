<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model;

use Magento\Framework\Filesystem\Io\IoInterface;
use Nextouch\ImportExport\Api\EntityDataOperationInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Psr\Log\LoggerInterface;
use function Lambdish\Phunctional\some;

class WinsEntityDataOperationCombiner implements EntityDataOperationInterface
{
    private const AT_SEMAPHORE_FILENAME = 'PROCEDI_AT.txt';
    private const ECAT_SEMAPHORE_FILENAME = 'PROCEDI_ECAT.txt';

    /** @var EntityDataOperationInterface[] */
    private array $operations;
    private IoInterface $client;
    private ImportExportConfig $config;
    private LoggerInterface $logger;

    public function __construct(
        array $operations,
        IoInterface $client,
        ImportExportConfig $config,
        LoggerInterface $logger
    ) {
        $this->operations = $operations;
        $this->client = $client;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function run(): void
    {
        $this->logger->info(__('Starting to run Wins entity data operations'));

        if ($this->containsSemaphoreFiles()) {
            \Lambdish\Phunctional\each(fn(EntityDataOperationInterface $item) => $item->run(), $this->operations);
            $this->removeSemaphoreFiles();
        } else {
            $this->logger->error('Failed to run entity data operations. Wins semaphore files do not exist.');
        }

        $this->logger->info(__('Finishing to run Wins entity data operations'));
    }

    private function containsSemaphoreFiles(): bool
    {
        $config = $this->config->getWinsConfig();
        $this->client->open($config);

        $winsLocation = $this->config->getWinsLocation();
        $this->client->cd($winsLocation);
        $files = $this->client->ls();
        $this->client->close();

        $containsATSemaphoreFile = fn(array $file) => $file['text'] === self::AT_SEMAPHORE_FILENAME;
        $containsECATSemaphoreFile = fn(array $file) => $file['text'] === self::ECAT_SEMAPHORE_FILENAME;

        return some($containsATSemaphoreFile, $files) && some($containsECATSemaphoreFile, $files);
    }

    private function removeSemaphoreFiles(): void
    {
        $config = $this->config->getWinsConfig();
        $this->client->open($config);

        $ATSemaphoreFilePath = $this->config->getWinsFilePath(self::AT_SEMAPHORE_FILENAME);
        $this->client->rm($ATSemaphoreFilePath);

        $ECATSemaphoreFilePath = $this->config->getWinsFilePath(self::ECAT_SEMAPHORE_FILENAME);
        $this->client->rm($ECATSemaphoreFilePath);

        $this->client->close();
    }
}

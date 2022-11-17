<?php
/** @noinspection PhpUnused */
declare(strict_types=1);

namespace Nextouch\ImportExport\Model;

use Magento\Framework\Filesystem\Io\IoInterface;
use Nextouch\ImportExport\Api\EntityDataOperationInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Psr\Log\LoggerInterface;
use function Lambdish\Phunctional\some;

class WinsEntityDataOperationCombiner implements EntityDataOperationInterface
{
    /** These constants are used inside di.xml */
    public const AT_SEMAPHORE_FILENAME = 'PROCEDI_AT.txt';
    public const ECAT_SEMAPHORE_FILENAME = 'PROCEDI_ECAT.txt';

    /** @var EntityDataOperationInterface[] */
    private array $operations;
    private string $semaphoreFilename;
    private IoInterface $client;
    private ImportExportConfig $config;
    private LoggerInterface $logger;

    public function __construct(
        array $operations,
        string $semaphoreFilename,
        IoInterface $client,
        ImportExportConfig $config,
        LoggerInterface $logger
    ) {
        $this->operations = $operations;
        $this->semaphoreFilename = $semaphoreFilename;
        $this->client = $client;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->logger->info(__('Starting to run Wins entity data operations'));

        if ($this->containsSemaphoreFiles()) {
            \Lambdish\Phunctional\each(fn(EntityDataOperationInterface $item) => $item->run(), $this->operations);
            $this->removeSemaphoreFile();
        } else {
            $this->logger->notice('Impossible to run entity data operations. Wins semaphore files do not exist.');
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

        $containsSemaphoreFile = fn(array $file) => $file['text'] === $this->semaphoreFilename;

        return some($containsSemaphoreFile, $files);
    }

    private function removeSemaphoreFile(): void
    {
        $config = $this->config->getWinsConfig();
        $this->client->open($config);

        $semaphoreFilePath = $this->config->getWinsFilePath($this->semaphoreFilename);
        $this->client->rm($semaphoreFilePath);

        $this->client->close();
    }
}

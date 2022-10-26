<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model;

use Nextouch\Core\Service\Filesystem\Io\IoInterface;
use Nextouch\ImportExport\Api\EntityDataOperationInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Psr\Log\LoggerInterface;

class WinsUnzipProductImagesOperation implements EntityDataOperationInterface
{
    private IoInterface $client;
    private ImportExportConfig $config;
    private LoggerInterface $logger;

    public function __construct(
        IoInterface $client,
        ImportExportConfig $config,
        LoggerInterface $logger
    ) {
        $this->client = $client;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function run(): void
    {
        $this->logger->info(__('Starting to unzip Wins product images'));

        try {
            $config = $this->config->getWinsConfig();
            $this->client->open($config);

            $this->client->exec('sh extract_command.sh');
        } finally {
            $this->logger->info(__('Finishing to unzip Wins product images'));
        }
    }
}

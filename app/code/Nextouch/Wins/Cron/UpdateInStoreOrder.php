<?php
declare(strict_types=1);

namespace Nextouch\Wins\Cron;

use Magento\Framework\Filesystem\Io\IoInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Nextouch\Wins\Service\Order\UpdateInStoreOrder as UpdateInStoreOrderService;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;

class UpdateInStoreOrder
{
    private UpdateInStoreOrderService $inStoreOrderService;
    private IoInterface $client;
    private ImportExportConfig $config;

    public function __construct(
        UpdateInStoreOrderService $inStoreOrderService,
        IoInterface $client,
        ImportExportConfig $config
    ) {
        $this->inStoreOrderService = $inStoreOrderService;
        $this->client = $client;
        $this->config = $config;
    }

    public function execute(): void
    {
        $this->openConnection();

        each(function (array $item) {
            $isSuccess = $this->inStoreOrderService->execute($item);

            if ($isSuccess) {
                $this->client->rm($item['filepath']);
            }
        }, $this->fetchInStoreOrders());

        $this->client->close();
    }

    private function openConnection(): void
    {
        $config = $this->config->getWinsConfig();
        $this->client->open($config);
    }

    private function fetchInStoreOrders(): array
    {
        $location = $this->config->getWinsLocation();
        $this->client->cd("$location/in_store_delivery");

        return map(function (array $data) {
            return [
                'filename' => $data['text'],
                'filepath' => $data['id'],
                'content' => $this->client->read($data['id']),
            ];
        }, $this->client->ls());
    }
}

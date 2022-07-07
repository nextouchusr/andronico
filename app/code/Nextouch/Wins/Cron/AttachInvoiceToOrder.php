<?php
declare(strict_types=1);

namespace Nextouch\Wins\Cron;

use Magento\Framework\Filesystem\Io\IoInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Nextouch\Wins\Service\Order\AttachInvoiceToOrder as AttachInvoiceToOrderService;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;

class AttachInvoiceToOrder
{
    private AttachInvoiceToOrderService $attachInvoiceService;
    private IoInterface $client;
    private ImportExportConfig $config;

    public function __construct(
        AttachInvoiceToOrderService $attachInvoiceService,
        IoInterface $client,
        ImportExportConfig $config
    ) {
        $this->attachInvoiceService = $attachInvoiceService;
        $this->client = $client;
        $this->config = $config;
    }

    public function execute(): void
    {
        $this->openConnection();

        each(function (array $item) {
            $isSuccess = $this->attachInvoiceService->execute($item);

            if ($isSuccess) {
                $this->client->rm($item['filepath']);
            }
        }, $this->fetchInvoices());

        $this->client->close();
    }

    private function openConnection(): void
    {
        $config = $this->config->getWinsConfig();
        $this->client->open($config);
    }

    private function fetchInvoices(): array
    {
        $location = $this->config->getWinsLocation();
        $this->client->cd("$location/invoices");

        return map(function (array $data) {
            return [
                'filename' => $data['text'],
                'filepath' => $data['id'],
                'content' => $this->client->read($data['id']),
            ];
        }, $this->client->ls());
    }
}

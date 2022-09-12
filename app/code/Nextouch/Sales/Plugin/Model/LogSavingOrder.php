<?php
declare(strict_types=1);

namespace Nextouch\Sales\Plugin\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class LogSavingOrder
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function beforeSave(OrderRepositoryInterface $subject, Order $order): array
    {
        $json = $order->convertToJson();
        $this->logger->info($json);

        return [$order];
    }
}

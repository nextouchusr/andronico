<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Plugin\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\InvoiceOrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Nextouch\FastEst\Service\ConfirmParkedDelivery as ConfirmParkedDeliveryService;

class ConfirmParkedDelivery
{
    private OrderRepositoryInterface $orderRepository;
    private ConfirmParkedDeliveryService $confirmParkedDeliveryService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ConfirmParkedDeliveryService $confirmParkedDeliveryService
    ) {
        $this->orderRepository = $orderRepository;
        $this->confirmParkedDeliveryService = $confirmParkedDeliveryService;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterExecute(InvoiceOrderInterface $subject, string $result, int $orderId): string
    {
        $order = $this->orderRepository->get($orderId);

        $this->confirmParkedDeliveryService->confirm($order);

        return $result;
    }
}

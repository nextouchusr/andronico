<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer\FastEst;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\FastEst\Service\ConfirmParkedDelivery as ConfirmParkedDeliveryService;

class ConfirmParkedDelivery implements ObserverInterface
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
     */
    public function execute(Observer $observer): void
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        $this->confirmParkedDeliveryService->confirm($order);
    }
}

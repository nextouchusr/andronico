<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order\InStore;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderManagementInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;

class OrderRejected implements OrderActionInterface
{
    private OrderRepositoryInterface $orderRepository;
    private OrderManagementInterface $orderManagement;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderManagementInterface $orderManagement
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderManagement = $orderManagement;
    }

    /**
     * @throws LocalizedException
     */
    public function execute(string $orderId): void
    {
        /** @var Order $order */
        $order = $this->orderRepository->getByIncrementId($orderId);

        if ($order->isPaid()) {
            return;
        }

        $this->orderManagement->cancel($order->getEntityId());
    }
}

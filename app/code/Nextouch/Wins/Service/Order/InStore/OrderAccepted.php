<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order\InStore;

use Magento\Framework\Exception\LocalizedException;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;
use Nextouch\Sales\Model\Order\Status;

class OrderAccepted implements OrderActionInterface
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
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

        $order->setStatus(Status::ACCEPTED['status']);
        $order->setStatus(Status::ACCEPTED['state']);
        $this->orderRepository->save($order);
    }
}

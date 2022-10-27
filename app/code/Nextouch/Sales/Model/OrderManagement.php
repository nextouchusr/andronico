<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model;

use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\OrderManagementInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order\Status;

class OrderManagement implements OrderManagementInterface
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function inCharge(string $incrementId): int
    {
        $order = $this->orderRepository->getByIncrementId($incrementId);
        $order->setStatus(Status::IN_CHARGE['status']);
        $order->setState(Status::IN_CHARGE['state']);
        $this->orderRepository->save($order);

        return (int) $order->getEntityId();
    }

    public function scheduled(string $incrementId): int
    {
        $order = $this->orderRepository->getByIncrementId($incrementId);
        $order->setStatus(Status::SCHEDULED['status']);
        $order->setState(Status::SCHEDULED['state']);
        $this->orderRepository->save($order);

        return (int) $order->getEntityId();
    }

    public function partiallyComplete(string $incrementId): int
    {
        $order = $this->orderRepository->getByIncrementId($incrementId);
        $order->setStatus(Status::PARTIALLY_COMPLETE['status']);
        $order->setState(Status::PARTIALLY_COMPLETE['state']);
        $this->orderRepository->save($order);

        return (int) $order->getEntityId();
    }

    public function complete(string $incrementId): int
    {
        $order = $this->orderRepository->getByIncrementId($incrementId);
        $order->setStatus(Status::COMPLETE['status']);
        $order->setState(Status::COMPLETE['state']);
        $this->orderRepository->save($order);

        return (int) $order->getEntityId();
    }
}

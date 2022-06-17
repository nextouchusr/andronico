<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model;

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

    public function inDelivery(int $id): bool
    {
        $order = $this->orderRepository->get($id);
        $order->setStatus(Status::IN_DELIVERY['status']);
        $order->setState(Status::IN_DELIVERY['state']);
        $this->orderRepository->save($order);

        return true;
    }

    public function inDeliveryByIncrementId(string $incrementId): bool
    {
        $order = $this->orderRepository->getByIncrementId($incrementId);
        $order->setStatus(Status::IN_DELIVERY['status']);
        $order->setState(Status::IN_DELIVERY['state']);
        $this->orderRepository->save($order);

        return true;
    }

    public function deliver(int $id): bool
    {
        $order = $this->orderRepository->get($id);
        $order->setStatus(Status::COMPLETE['status']);
        $order->setState(Status::COMPLETE['state']);
        $this->orderRepository->save($order);

        return true;
    }

    public function deliverByIncrementId(string $incrementId): bool
    {
        $order = $this->orderRepository->getByIncrementId($incrementId);
        $order->setStatus(Status::COMPLETE['status']);
        $order->setState(Status::COMPLETE['state']);
        $this->orderRepository->save($order);

        return true;
    }
}

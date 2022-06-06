<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Sales\Api\OrderManagementInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order\Status;
use Nextouch\Sales\Model\ResourceModel\Order\CollectionFactory;

class OrderManagement implements OrderManagementInterface
{
    private CollectionFactory $collectionFactory;
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        CollectionFactory $collectionFactory,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->orderRepository = $orderRepository;
    }

    public function inDelivery(int $id): bool
    {
        return $this->changeStatus($id, Status::IN_DELIVERY);
    }

    public function inDeliveryByIncrementId(string $incrementId): bool
    {
        $order = $this->orderRepository->getByIncrementId($incrementId);

        return $this->changeStatus((int) $order->getId(), Status::IN_DELIVERY);
    }

    public function deliver(int $id): bool
    {
        return $this->changeStatus($id, Status::DELIVERED);
    }

    public function deliverByIncrementId(string $incrementId): bool
    {
        $order = $this->orderRepository->getByIncrementId($incrementId);

        return $this->changeStatus((int) $order->getId(), Status::DELIVERED);
    }

    /**
     * @throws NoSuchEntityException
     * @throws AlreadyExistsException
     * @throws InputException
     */
    private function changeStatus(int $id, array $status): bool
    {
        $order = $this->orderRepository->get($id);
        $order->setStatus($status['status']);
        $order->setState($status['state']);

        $this->orderRepository->save($order);

        return true;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Sales\Api\Data\OrderInterface;
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

    public function deliver(int $id): bool
    {
        $order = $this->orderRepository->get($id);
        $order->setStatus(Status::DELIVERED['status']);
        $order->setState(Status::DELIVERED['state']);

        $this->orderRepository->save($order);

        return true;
    }

    public function deliverByIncrementId(string $incrementId): bool
    {
        /** @var OrderInterface $order */
        $order = $this->collectionFactory
            ->create()
            ->addFieldToFilter(OrderInterface::INCREMENT_ID, $incrementId)
            ->getFirstItem();

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('The order that was requested does not exist.'));
        }

        $order->setStatus(Status::DELIVERED['status']);
        $order->setState(Status::DELIVERED['state']);

        $this->orderRepository->save($order);

        return true;
    }
}

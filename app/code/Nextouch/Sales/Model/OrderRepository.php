<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\ResourceModel\Order\CollectionFactory;

class OrderRepository implements OrderRepositoryInterface
{
    private CollectionFactory $collectionFactory;
    private \Magento\Sales\Api\OrderRepositoryInterface $orderRepository;

    public function __construct(
        CollectionFactory $collectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->orderRepository = $orderRepository;
    }

    public function get(int $id): OrderInterface
    {
        /** @var OrderInterface $order */
        $order = $this->collectionFactory
            ->create()
            ->addFieldToFilter('entity_id', $id)
            ->getFirstItem();

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('The order that was requested does not exist.'));
        }

        return $order;
    }

    public function getByIncrementId(string $incrementId): OrderInterface
    {
        /** @var OrderInterface $order */
        $order = $this->collectionFactory
            ->create()
            ->addFieldToFilter(OrderInterface::INCREMENT_ID, $incrementId)
            ->getFirstItem();

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('The order that was requested does not exist.'));
        }

        return $order;
    }

    public function save(OrderInterface $order): OrderInterface
    {
        $this->orderRepository->save($order);

        return $this->get((int) $order->getEntityId());
    }
}

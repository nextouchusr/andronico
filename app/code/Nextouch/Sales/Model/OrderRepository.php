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

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function getByIncrementId(string $incrementId): OrderInterface
    {
        /** @var OrderInterface $order */
        $order = $this->collectionFactory
            ->create()
            ->addFieldToFilter('increment_id', $incrementId)
            ->getFirstItem();

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('The order that was requested does not exist.'));
        }

        return $order;
    }
}

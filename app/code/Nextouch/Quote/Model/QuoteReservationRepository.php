<?php
declare(strict_types=1);

namespace Nextouch\Quote\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Quote\Api\CartReservationRepositoryInterface;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;

class QuoteReservationRepository implements CartReservationRepositoryInterface
{
    private QuoteCollectionFactory $collectionFactory;

    public function __construct(QuoteCollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function getByReservedOrderId(string $reservedOrderId): CartInterface
    {
        /** @var CartInterface $quote */
        $quote = $this->collectionFactory
            ->create()
            ->addFieldToFilter('reserved_order_id', $reservedOrderId)
            ->getFirstItem();

        if (!$quote->getId()) {
            throw new NoSuchEntityException(__('The quote that was requested does not exist.'));
        }

        return $quote;
    }
}

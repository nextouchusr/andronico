<?php
declare(strict_types=1);

namespace Nextouch\Rma\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Rma\Api\Data\RmaInterface;
use Nextouch\Rma\Api\RmaRepositoryInterface;
use Nextouch\Rma\Model\ResourceModel\Rma\CollectionFactory;

class RmaRepository implements RmaRepositoryInterface
{
    private CollectionFactory $collectionFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private \Magento\Rma\Api\RmaRepositoryInterface $rmaRepository;

    public function __construct(
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        \Magento\Rma\Api\RmaRepositoryInterface $rmaRepository
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->rmaRepository = $rmaRepository;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): array
    {
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var RmaInterface[] $items */
        $items = $collection->getItems();

        return $items;
    }

    public function get(int $id): RmaInterface
    {
        /** @var RmaInterface $return */
        $return = $this->collectionFactory
            ->create()
            ->addFieldToFilter('entity_id', $id)
            ->getFirstItem();

        if (!$return->getId()) {
            throw new NoSuchEntityException(__('The return that was requested does not exist.'));
        }

        return $return;
    }

    public function getByIncrementId(string $incrementId): RmaInterface
    {
        /** @var RmaInterface $return */
        $return = $this->collectionFactory
            ->create()
            ->addFieldToFilter('increment_id', $incrementId)
            ->getFirstItem();

        if (!$return->getId()) {
            throw new NoSuchEntityException(__('The return that was requested does not exist.'));
        }

        return $return;
    }

    /**
     * @noinspection PhpCastIsUnnecessaryInspection
     */
    public function save(RmaInterface $return): RmaInterface
    {
        $this->rmaRepository->save($return);

        return $this->get((int) $return->getEntityId());
    }
}

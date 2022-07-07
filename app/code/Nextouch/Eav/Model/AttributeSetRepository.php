<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Eav\Api\AttributeSetRepositoryInterface;
use Nextouch\Eav\Api\Data\AttributeSetInterface;
use Nextouch\Eav\Model\ResourceModel\AttributeSet\CollectionFactory as AttributeSetCollectionFactory;

class AttributeSetRepository implements AttributeSetRepositoryInterface
{
    private AttributeSetCollectionFactory $attributeSetCollectionFactory;
    private \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSetRepository;

    public function __construct(
        AttributeSetCollectionFactory $attributeSetCollectionFactory,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSetRepository
    ) {
        $this->attributeSetCollectionFactory = $attributeSetCollectionFactory;
        $this->attributeSetRepository = $attributeSetRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function getByExternalSetId(string $externalSetId): AttributeSetInterface
    {
        /** @var AttributeSetInterface $attributeSet */
        $attributeSet = $this->attributeSetCollectionFactory
            ->create()
            ->addFieldToFilter(AttributeSetInterface::EXTERNAL_SET_ID, $externalSetId)
            ->getFirstItem();

        if (!$attributeSet->getAttributeSetId()) {
            throw new NoSuchEntityException(__('The attribute set that was requested does not exist.'));
        }

        return $attributeSet;
    }

    /**
     * {@inheritDoc}
     */
    public function save(AttributeSetInterface $attributeSet): AttributeSetInterface
    {
        $this->attributeSetRepository->save($attributeSet);

        return $attributeSet;
    }
}

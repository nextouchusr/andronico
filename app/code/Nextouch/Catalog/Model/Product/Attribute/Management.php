<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model\Product\Attribute;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Nextouch\Catalog\Api\ProductAttributeManagementInterface;
use Nextouch\Eav\Api\AttributeManagementInterface;

class Management implements ProductAttributeManagementInterface
{
    private AttributeManagementInterface $eavAttributeManagement;
    private CollectionFactory $collectionFactory;

    public function __construct(
        AttributeManagementInterface $eavAttributeManagement,
        CollectionFactory $collectionFactory
    ) {
        $this->eavAttributeManagement = $eavAttributeManagement;
        $this->collectionFactory = $collectionFactory;
    }

    public function getUserDefinedAttributes(string $attributeSetId): array
    {
        return $this->eavAttributeManagement->getUserDefinedAttributes(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            $attributeSetId
        );
    }

    public function getFilterableAttributes(): array
    {
        $productAttributes = $this->collectionFactory->create();
        $productAttributes->addFieldToFilter(
            ['is_filterable', 'is_filterable_in_search'],
            [[1, 2], 1]
        );

        return $productAttributes->getItems();
    }
}

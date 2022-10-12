<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model\Product\Attribute;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Nextouch\Catalog\Api\ProductAttributeManagementInterface;
use Nextouch\Eav\Api\AttributeManagementInterface;

class Management implements ProductAttributeManagementInterface
{
    private AttributeManagementInterface $eavAttributeManagement;

    public function __construct(AttributeManagementInterface $eavAttributeManagement)
    {
        $this->eavAttributeManagement = $eavAttributeManagement;
    }

    public function getDecisionTreeAttributes(string $attributeSetIds): array
    {
        return $this->eavAttributeManagement->getDecisionTreeAttributes(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            $attributeSetIds
        );
    }
}

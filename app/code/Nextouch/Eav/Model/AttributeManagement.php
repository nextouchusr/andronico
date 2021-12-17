<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model;

use Magento\Eav\Api\Data\AttributeInterface;
use Nextouch\Eav\Api\AttributeManagementInterface;
use function Lambdish\Phunctional\filter;

class AttributeManagement implements AttributeManagementInterface
{
    private \Magento\Eav\Api\AttributeManagementInterface $attributeManagement;

    public function __construct(\Magento\Eav\Api\AttributeManagementInterface $attributeManagement)
    {
        $this->attributeManagement = $attributeManagement;
    }

    public function getUserDefinedAttributes(string $entityTypeCode, string $attributeSetId): array
    {
        $attributes = $this->attributeManagement->getAttributes($entityTypeCode, $attributeSetId);

        return filter(fn(AttributeInterface $item) => (bool) $item->getIsUserDefined(), $attributes);
    }
}

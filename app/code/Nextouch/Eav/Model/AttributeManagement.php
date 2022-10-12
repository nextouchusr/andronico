<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model;

use Nextouch\Eav\Api\AttributeManagementInterface;

class AttributeManagement implements AttributeManagementInterface
{
    private const ATTRIBUTE_SET_SEPARATOR = ',';

    private \Magento\Eav\Api\AttributeManagementInterface $attributeManagement;

    public function __construct(\Magento\Eav\Api\AttributeManagementInterface $attributeManagement)
    {
        $this->attributeManagement = $attributeManagement;
    }

    public function getDecisionTreeAttributes(string $entityTypeCode, string $attributeSetIds): array
    {
        $attributeSetIds = explode(self::ATTRIBUTE_SET_SEPARATOR, $attributeSetIds);
        $uniqueAttributes = [];

        foreach ($attributeSetIds as $attributeSetId) {
            $attributes = $this->attributeManagement->getAttributes($entityTypeCode, $attributeSetId);

            foreach ($attributes as $attribute) {
                $attributeCode = $attribute->getAttributeCode();

                if (!in_array($attributeCode, array_keys($uniqueAttributes))) {
                    $uniqueAttributes[$attributeCode] = $attribute;
                }
            }
        }

        return $uniqueAttributes;
    }
}

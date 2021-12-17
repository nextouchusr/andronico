<?php
declare(strict_types=1);

namespace Nextouch\Eav\Api;

/**
 * @api
 */
interface AttributeManagementInterface
{
    /**
     * @param string $entityTypeCode
     * @param string $attributeSetId
     * @return \Magento\Eav\Api\Data\AttributeInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUserDefinedAttributes(string $entityTypeCode, string $attributeSetId): array;
}

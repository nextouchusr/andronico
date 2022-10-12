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
     * @param string $attributeSetIds
     * @return \Magento\Eav\Api\Data\AttributeInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDecisionTreeAttributes(string $entityTypeCode, string $attributeSetIds): array;
}

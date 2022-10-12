<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api;

/**
 * @api
 */
interface ProductAttributeManagementInterface
{
    /**
     * @param string $attributeSetIds
     * @return \Magento\Catalog\Api\Data\ProductAttributeInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDecisionTreeAttributes(string $attributeSetIds): array;
}

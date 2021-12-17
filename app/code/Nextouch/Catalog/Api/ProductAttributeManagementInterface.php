<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api;

/**
 * @api
 */
interface ProductAttributeManagementInterface
{
    /**
     * @param string $attributeSetId
     * @return \Magento\Catalog\Api\Data\ProductAttributeInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUserDefinedAttributes(string $attributeSetId): array;
}

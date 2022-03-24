<?php
declare(strict_types=1);

namespace Nextouch\Eav\Api;

use Magento\Eav\Api\Data\AttributeGroupInterface;

/**
 * @api
 */
interface AttributeGroupRepositoryInterface extends \Magento\Eav\Api\AttributeGroupRepositoryInterface
{
    /**
     * @param int $attributeSetId
     * @return \Magento\Eav\Api\Data\AttributeGroupInterface[]
     */
    public function getItemsByAttributeSetId(int $attributeSetId): array;

    /**
     * @param int $attributeSetId
     * @param string $groupCode
     * @return \Magento\Eav\Api\Data\AttributeGroupInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByAttributeGroupCode(int $attributeSetId, string $groupCode): AttributeGroupInterface;
}

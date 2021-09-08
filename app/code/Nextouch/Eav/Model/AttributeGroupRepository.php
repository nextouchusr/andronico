<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model;

use Magento\Eav\Api\Data\AttributeGroupInterface;
use Magento\Eav\Model\Attribute\GroupRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Eav\Api\AttributeGroupRepositoryInterface;

class AttributeGroupRepository extends GroupRepository implements AttributeGroupRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByAttributeGroupCode(int $attributeSetId, string $groupCode): AttributeGroupInterface
    {
        /** @var AttributeGroupInterface $attributeGroup */
        $attributeGroup = $this->groupListFactory
            ->create()
            ->addFieldToFilter('attribute_set_id', $attributeSetId)
            ->addFieldToFilter('attribute_group_code', $groupCode)
            ->getFirstItem();

        if (!$attributeGroup->getAttributeGroupId()) {
            throw new NoSuchEntityException(__('The attribute group that was requested does not exist.'));
        }

        return $attributeGroup;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model\ResourceModel\AttributeOption;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option as AttributeOptionResourceModel;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection as AttributeOptionCollection;
use Nextouch\Eav\Api\Data\AttributeOptionInterface;
use Nextouch\Eav\Model\AttributeOption;

class Collection extends AttributeOptionCollection
{
    protected function _construct(): void
    {
        $this->_init(AttributeOption::class, AttributeOptionResourceModel::class);
        $this->_optionValueTable = $this->_coreResource->getTableName('eav_attribute_option_value');
    }

    public function joinAttributeOptionValue(): self
    {
        $this->join(
            ['aov' => 'eav_attribute_option_value'],
            'aov.option_id = main_table.option_id',
            ['label' => 'aov.value', 'value' => 'main_table.option_id']
        );

        return $this;
    }

    public function addAttributeFilter(string $entityTypeCode, string $attributeCode): self
    {
        $this->join(['a' => 'eav_attribute'], 'a.attribute_id = main_table.attribute_id', ['attribute_code']);
        $this->join(['et' => 'eav_entity_type'], 'et.entity_type_id = a.entity_type_id', ['entity_type_code']);
        $this->addFieldToFilter('entity_type_code', ['eq' => $entityTypeCode]);
        $this->addFieldToFilter('attribute_code', ['eq' => $attributeCode]);

        return $this;
    }

    public function addExternalOptionIdFilter(string $externalOptionId): self
    {
        $this->addFieldToFilter(AttributeOptionInterface::EXTERNAL_OPTION_ID, $externalOptionId);

        return $this;
    }
}

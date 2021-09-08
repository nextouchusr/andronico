<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model\ResourceModel\AttributeOption;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option as AttributeOptionResourceModel;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection as AttributeOptionCollection;
use Nextouch\Eav\Model\AttributeOption;

class Collection extends AttributeOptionCollection
{
    protected function _construct(): void
    {
        $this->_init(AttributeOption::class, AttributeOptionResourceModel::class);
        $this->_optionValueTable = $this->_coreResource->getTableName('eav_attribute_option_value');
    }
}

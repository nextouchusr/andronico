<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model\ResourceModel\AttributeOption;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option as AttributeOptionResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nextouch\Eav\Model\AttributeOption;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(AttributeOption::class, AttributeOptionResourceModel::class);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model\ResourceModel\AttributeSet;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set as AttributeSetResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nextouch\Eav\Model\AttributeSet;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(AttributeSet::class, AttributeSetResourceModel::class);
    }
}

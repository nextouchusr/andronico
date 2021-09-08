<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model\ResourceModel\AttributeSet;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set as AttributeSetResourceModel;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection as AttributeSetCollection;
use Nextouch\Eav\Model\AttributeSet;

class Collection extends AttributeSetCollection
{
    protected function _construct(): void
    {
        $this->_init(AttributeSet::class, AttributeSetResourceModel::class);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Quote\Model\ResourceModel\Quote\Item;

use Magento\Quote\Model\ResourceModel\Quote\Item as ItemResourceModel;
use Magento\Quote\Model\ResourceModel\Quote\Item\Collection as ItemCollection;
use Nextouch\Quote\Model\Quote\Item;

class Collection extends ItemCollection
{
    protected function _construct(): void
    {
        $this->_init(Item::class, ItemResourceModel::class);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\ResourceModel\Order\Item;

use Magento\Sales\Model\ResourceModel\Order\Item as ItemResourceModel;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection as ItemCollection;
use Nextouch\Sales\Model\Order\Item;

class Collection extends ItemCollection
{
    protected function _construct(): void
    {
        $this->_init(Item::class, ItemResourceModel::class);
    }
}

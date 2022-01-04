<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\ResourceModel\Order\Shipment\Item;

use Magento\Sales\Model\ResourceModel\Order\Shipment\Item as ItemResourceModel;
use Magento\Sales\Model\ResourceModel\Order\Shipment\Item\Collection as ItemCollection;
use Nextouch\Sales\Model\Order\Shipment\Item;

class Collection extends ItemCollection
{
    protected function _construct(): void
    {
        $this->_init(Item::class, ItemResourceModel::class);
    }
}

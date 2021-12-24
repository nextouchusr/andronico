<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\ResourceModel\Order;

use Magento\Sales\Model\ResourceModel\Order as OrderResourceModel;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Nextouch\Sales\Model\Order;

class Collection extends OrderCollection
{
    protected function _construct(): void
    {
        $this->_init(Order::class, OrderResourceModel::class);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\ResourceModel\Order\Address;

use Magento\Sales\Model\ResourceModel\Order\Address as AddressResourceModel;
use Magento\Sales\Model\ResourceModel\Order\Address\Collection as AddressCollection;
use Nextouch\Sales\Model\Order\Address;

class Collection extends AddressCollection
{
    protected function _construct(): void
    {
        $this->_init(Address::class, AddressResourceModel::class);
    }
}

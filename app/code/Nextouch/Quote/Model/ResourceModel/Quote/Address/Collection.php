<?php
declare(strict_types=1);

namespace Nextouch\Quote\Model\ResourceModel\Quote\Address;

use Magento\Quote\Model\ResourceModel\Quote\Address as AddressResourceModel;
use Magento\Quote\Model\ResourceModel\Quote\Address\Collection as AddressCollection;
use Nextouch\Quote\Model\Quote\Address;

class Collection extends AddressCollection
{
    protected function _construct(): void
    {
        $this->_init(Address::class, AddressResourceModel::class);
    }
}

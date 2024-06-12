<?php

namespace Nextouch\GestLogis\Model;

use Magento\Framework\Model\AbstractModel;
use Nextouch\GestLogis\Model\ResourceModel\ShippingServices as ShippingServicesResource;

class ShippingServices extends AbstractModel
{
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ShippingServicesResource::class);
    }
}

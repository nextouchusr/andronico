<?php

namespace Nextouch\GestLogis\Model;

use Magento\Framework\Model\AbstractModel;
use Nextouch\GestLogis\Model\ResourceModel\ShippingPostcode as ShippingPostcodeResource;

class ShippingPostcode extends AbstractModel
{
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ShippingPostcodeResource::class);
    }
}

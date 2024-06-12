<?php

namespace Nextouch\GestLogis\Model;

use Magento\Framework\Model\AbstractModel;
use Nextouch\GestLogis\Model\ResourceModel\QuoteShippingService as QuoteShippingServiceResource;

class QuoteShippingService extends AbstractModel
{
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(QuoteShippingServiceResource::class);
    }
}

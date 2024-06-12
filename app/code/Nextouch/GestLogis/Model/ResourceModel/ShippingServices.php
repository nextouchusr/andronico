<?php

namespace Nextouch\GestLogis\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShippingServices extends AbstractDb
{
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('nextouch_gestlogis_shipping_services', 'entity_id');
    }
}

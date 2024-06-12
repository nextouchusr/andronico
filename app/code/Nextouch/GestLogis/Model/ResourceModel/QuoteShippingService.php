<?php

namespace Nextouch\GestLogis\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class QuoteShippingService extends AbstractDb
{
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('nextouch_gestlogis_quote_shipping_service', 'entity_id');
    }
}

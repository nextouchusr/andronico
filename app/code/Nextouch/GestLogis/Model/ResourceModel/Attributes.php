<?php

namespace Nextouch\GestLogis\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Attributes extends AbstractDb
{
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('nextouch_gestlogis_attributes', 'entity_id');
    }
}

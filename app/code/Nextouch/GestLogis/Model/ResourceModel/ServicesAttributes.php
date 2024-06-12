<?php

namespace Nextouch\GestLogis\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ServicesAttributes extends AbstractDb
{
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('nextouch_gestlogis_services_attributes', 'entity_id');
    }
}

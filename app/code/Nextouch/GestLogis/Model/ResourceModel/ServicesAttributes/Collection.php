<?php

namespace Nextouch\GestLogis\Model\ResourceModel\ServicesAttributes;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nextouch\GestLogis\Model\ServicesAttributes as ServicesAttributesModel;
use Nextouch\GestLogis\Model\ResourceModel\ServicesAttributes as ServicesAttributesResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'nextouch_gestlogis_services_attributes';

    /**
     * @var string
     */
    protected $_eventObject = 'nextouch_gestlogis_services_attributes';

    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ServicesAttributesModel::class, ServicesAttributesResource::class);
    }
}

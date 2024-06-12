<?php

namespace Nextouch\GestLogis\Model\ResourceModel\PostcodeServiceAttributes;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nextouch\GestLogis\Model\PostcodeServiceAttributes as PostcodeServiceAttributesModel;
use Nextouch\GestLogis\Model\ResourceModel\PostcodeServiceAttributes as PostcodeServiceAttributesResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'nextouch_gestlogis_postcode_service_attributes';

    /**
     * @var string
     */
    protected $_eventObject = 'nextouch_gestlogis_postcode_service_attributes';

    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(PostcodeServiceAttributesModel::class, PostcodeServiceAttributesResource::class);
    }
}

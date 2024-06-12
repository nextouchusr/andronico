<?php

namespace Nextouch\GestLogis\Model\ResourceModel\Attributes;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nextouch\GestLogis\Model\Attributes as AttributesModel;
use Nextouch\GestLogis\Model\ResourceModel\Attributes as AttributesResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'nextouch_gestlogis_attributes';

    /**
     * @var string
     */
    protected $_eventObject = 'nextouch_gestlogis_attributes';

    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(AttributesModel::class, AttributesResource::class);
    }

    /**
     * GetAttributeById
     *
     * @param int $entityId
     * @return object|null
     */
    public function getAttributeById($entityId)
    {
        $this->addFieldToFilter('entity_id', ['eq' => $entityId]);
        $attribute = $this->getFirstItem();

        return $attribute;
    }
}

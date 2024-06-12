<?php

namespace Nextouch\GestLogis\Model\ResourceModel\ShippingServices;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nextouch\GestLogis\Model\ShippingServices as ShippingServicesModel;
use Nextouch\GestLogis\Model\ResourceModel\ShippingServices as ShippingServicesResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'nextouch_gestlogis_shipping_services';

    /**
     * @var string
     */
    protected $_eventObject = 'nextouch_gestlogis_shipping_services';

    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ShippingServicesModel::class, ShippingServicesResource::class);
    }

    /**
     * IsServicesAvailable
     *
     * @param int $attributeSetId
     * @return boolean
     */
    public function isServicesAvailable($attributeSetId)
    {
        $this->addFieldToFilter('main_table.status', ['eq' => 1]);

        $this->getSelect()->join(
            ['ngsa' => $this->getTable('nextouch_gestlogis_services_attributes')],
            'main_table.entity_id = ngsa.parent_id AND ngsa.attribute_set_id = ' . $attributeSetId,
            ['*']
        );

        $this->getSelect()->join(
            ['nga' => $this->getTable('nextouch_gestlogis_attributes')],
            'ngsa.attribute_id = nga.entity_id',
            ['*']
        );

        if ($this->getSize()) {
            return true;
        }

        return false;
    }

    /**
     * GetServiceById
     *
     * @param int $entityId
     * @return object|null
     */
    public function getServiceById($entityId)
    {
        $this->addFieldToFilter('main_table.status', ['eq' => 1]);
        $this->addFieldToFilter('main_table.entity_id', ['eq' => $entityId]);

        if ($this->getSize()) {
            return $this->getFirstItem();
        }

        return null;
    }

    /**
     * _getServiceById
     *
     * @param int $entityId
     * @param int $postCode
     * @param int $attributeSetId
     * @return object
     */
    public function _getServiceById($entityId, $postCode, $attributeSetId)
    {
        $this->addFieldToFilter('main_table.status', ['eq' => 1]);
        $this->addFieldToFilter('main_table.entity_id', ['eq' => $entityId]);

        $this->getSelect()->joinLeft(
            ['ngsp' => $this->getTable('nextouch_gestlogis_shipping_postcode')],
            'ngsp.status = 1 AND ngsp.postcode = ' . $postCode,
            [
                "ngsp_postcode" => "ngsp.postcode",
            ]
        );

        $this->getSelect()->joinLeft(
            ['ngps' => $this->getTable('nextouch_gestlogis_postcode_service_attributes')],
            'ngsp.entity_id = ngps.postcode_id AND ngps.service_id = ' . $entityId . ' AND ngps.attribute_set_id = ' . $attributeSetId,
            [
                "attribute_set_id" => "ngps.attribute_set_id",
                "postcode_price"   => "ngps.postcode_price",
                "service_price"    => "ngps.service_price",
                "is_service_required"    => "ngps.is_service_required",
            ]
        );

        $this->getSelect()->group('ngsp.entity_id');


        return $this->getFirstItem();
    }
}

<?php

namespace Nextouch\GestLogis\Model\ResourceModel\ShippingPostcode;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nextouch\GestLogis\Model\ShippingPostcode as ShippingPostcodeModel;
use Nextouch\GestLogis\Model\ResourceModel\ShippingPostcode as ShippingPostcodeResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'nextouch_gestlogis_shipping_postcode';

    /**
     * @var string
     */
    protected $_eventObject = 'nextouch_gestlogis_shipping_postcode';

    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ShippingPostcodeModel::class, ShippingPostcodeResource::class);
    }

    /**
     * GetShippingPrice
     *
     * @param int $postcode
     * @return object
     */
    public function getShippingPrice($postcode)
    {
        $this->addFieldToFilter('status', ['eq' => 1]);
        $this->addFieldToFilter('postcode', ['eq' => $postcode]);

        $this->getSelect()->group('main_table.entity_id');

        $this->getSelect()->joinLeft(
            ['ngpsa' => $this->getTable('nextouch_gestlogis_postcode_service_attributes')],
            'main_table.entity_id = ngpsa.postcode_id',
            [
                "service_id"       => "service_id",
                "attribute_set_id" => "attribute_set_id",
                "attribute_id"     => "attribute_id",
                "postcode_price"   => "postcode_price",
                "service_price"    => "service_price",
                "is_service_required"    => "is_service_required"
            ]
        );

        $shipping = $this->getFirstItem();

        return $shipping;
    }

    /**
     * getPostcodeAttributesCollection
     *
     * @param int $attributeSetId
     * @param int $postcode
     * @param bool $includeJoin
     * @param bool $includeAttributeTbl
     * @param bool $addFilterForAttributeIdNull
     * @return $this
     */
    public function getPostcodeAttributesCollection(
        $attributeSetId,
        $postcode,
        $includeJoin = true,
        $includeAttributeTbl = true,
        $addFilterForAttributeIdNull = false
    ) {
        $this->addFieldToFilter('status', ['eq' => 1]);
        $this->addFieldToFilter('postcode', ['eq' => $postcode]);

        $this->getSelect()->joinLeft(
            ['ngpsa' => $this->getTable('nextouch_gestlogis_postcode_service_attributes')],
            'main_table.entity_id = ngpsa.postcode_id AND ngpsa.attribute_set_id = ' . $attributeSetId,
            [
                "service_id"       => "service_id",
                "attribute_set_id" => "attribute_set_id",
                "attribute_id"     => "attribute_id",
                "postcode_price"   => "postcode_price",
                "service_price"    => "service_price",
                "is_service_required"    => "is_service_required"
            ]
        );

        // $this->addFieldToFilter('ngpsa.service_price', ['neq' => 0]);

        if ($includeJoin) {
            $this->getSelect()->group('ngpsa.attribute_id');
        }

        //if ($addFilterForAttributeIdNull) {
            $cloneCollection = clone $this;
            $cloneCollection->addFieldToFilter('ngpsa.attribute_id', ['null' => true]);

            if ($cloneCollection->getSize()) {
                return $cloneCollection;
            }
        //}

        if ($includeAttributeTbl) {
            $this->getSelect()->join(
                ['nga' => 'nextouch_gestlogis_attributes'],
                'ngpsa.attribute_id = nga.entity_id',
                [
                    "attribute_code"  => "attribute_code",
                    "attribute_value" => "attribute_value"
                ]
            );
        }

        return $this;
    }

    /**
     * IsPostcodeAvailable
     *
     * @param int $postcode
     * @return boolean
     */
    public function isPostcodeAvailable($postcode)
    {
        $this->addFieldToFilter('status', ['eq' => 1]);
        $this->addFieldToFilter('postcode', ['eq' => $postcode]);

        if ($this->getSize()) {
            return true;
        }

        return false;
    }
}

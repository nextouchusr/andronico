<?php

namespace Nextouch\GestLogis\Model\ResourceModel\QuoteShippingService;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nextouch\GestLogis\Model\QuoteShippingService as QuoteShippingServiceModel;
use Nextouch\GestLogis\Model\ResourceModel\QuoteShippingService as QuoteShippingServiceResource;

class Collection extends AbstractCollection
{
    public const GESTLOGIS_DEFAULT_SHIP_CODE = 'default';

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'nextouch_gestlogis_quote_shipping_service';

    /**
     * @var string
     */
    protected $_eventObject = 'nextouch_gestlogis_quote_shipping_service';

    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(QuoteShippingServiceModel::class, QuoteShippingServiceResource::class);
    }

    /**
     * IsSameServiceExistsWithItem
     *
     * @param int $itemId
     * @param string $serviceCode
     * @return boolean
     */
    public function isSameServiceExistsWithItem($itemId, $serviceCode)
    {
        $this->addFieldToFilter('item_id', ['eq' => $itemId]);
        $this->addFieldToFilter('service_code', ['eq' => $serviceCode]);

        if ($this->getSize()) {
            return true;
        }

        return false;
    }

    /**
     * GetServicesByItemId
     *
     * @param int $itemId
     * @param boolean $excludeJoin
     * @return object
     */
    public function getServicesByItemId($itemId, $excludeJoin = false)
    {
        $this->addFieldToFilter('item_id', ['eq' => $itemId]);

        if (!$excludeJoin) {
            $this->getSelect()->join(
                ['ngss' => $this->getTable('nextouch_gestlogis_shipping_services')],
                'main_table.service_code = ngss.service_code',
                ['tooltip']
            );
        }

        return $this;
    }

    /**
     * GetQuoteDefaultShipPrice
     *
     * @param int $itemId
     * @return int|null
     */
    public function getQuoteDefaultShipPrice($itemId)
    {
        $this->addFieldToFilter('item_id', ['eq' => $itemId]);
        $this->addFieldToFilter('service_code', ['eq' => self::GESTLOGIS_DEFAULT_SHIP_CODE]);

        if ($this->getSize()) {
            if ($defaultService = $this->getFirstItem()) {
                return (float) $defaultService->getPrice();
            }
        }

        return null;
    }
}

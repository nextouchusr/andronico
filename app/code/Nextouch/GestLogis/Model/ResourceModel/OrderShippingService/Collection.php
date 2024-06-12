<?php

namespace Nextouch\GestLogis\Model\ResourceModel\OrderShippingService;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nextouch\GestLogis\Model\OrderShippingService as OrderShippingServiceModel;
use Nextouch\GestLogis\Model\ResourceModel\OrderShippingService as OrderShippingServiceResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'nextouch_gestlogis_order_shipping_service';

    /**
     * @var string
     */
    protected $_eventObject = 'nextouch_gestlogis_order_shipping_service';

    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(OrderShippingServiceModel::class, OrderShippingServiceResource::class);
    }

    /**
     * GetServicesList
     *
     * @param int $itemId
     * @return $this
     */
    public function getServicesList($itemId)
    {
        $this->getSelect()->join(
            ['soi' => $this->getTable('sales_order_item')],
            'main_table.item_id = soi.item_id AND soi.item_id = ' . $itemId,
            ['quote_item_id']
        );

        return $this;
    }
}

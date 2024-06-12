<?php

namespace Nextouch\GestLogis\Block\Adminhtml\Sales\Order;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Nextouch\GestLogis\Helper\Data as DataHelper;

class OrderService extends Template
{
    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * __construct
     *
     * @param Context $context
     * @param DataHelper $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        DataHelper $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_dataHelper = $dataHelper;
    }

    /**
     * DataHelperObj
     *
     * @return DataHelper
     */
    public function dataHelperObj()
    {
        return $this->_dataHelper;
    }

    /**
     * GetServicesByItemId
     *
     * @param int $itemId
     * @return object
     */
    public function getServicesByItemId($itemId)
    {
        $orderShippingCollection = $this->_dataHelper->getOrderShippingServiceCollectionObj();
        $service = $orderShippingCollection->getServicesList($itemId);

        return $service;
    }
}

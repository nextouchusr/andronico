<?php

namespace Nextouch\GestLogis\Block\Checkout\Cart;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Nextouch\GestLogis\Helper\Data as DataHelper;

class QuoteService extends Template
{
    /**
     * @var PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * __construct
     *
     * @param Context $context
     * @param PriceCurrencyInterface $priceCurrency
     * @param DataHelper $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        PriceCurrencyInterface $priceCurrency,
        DataHelper $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_priceCurrency = $priceCurrency;
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
        $service = $this->_dataHelper->getServicesByItemId($itemId);

        return $service;
    }

    /**
     * Function getFormatedPrice
     *
     * @param float $price
     *
     * @return string
     */
    public function getFormatedPrice($amount)
    {
        return $this->_priceCurrency->convertAndFormat($amount);
    }
}

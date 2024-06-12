<?php

namespace Nextouch\GestLogis\Plugin\Magento\Checkout\CustomerData;

use Magento\Checkout\CustomerData\DefaultItem as MagentoDefaultItem;
use Magento\Quote\Model\Quote\Item;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Nextouch\GestLogis\Block\Checkout\Cart\QuoteService;
use Psr\Log\LoggerInterface;

class DefaultItem
{
    /**
     * @var DataHelper
     */
    private $_dataHelper;

    /**
     * @var QuoteService
     */
    private $_quoteService;

    /**
     * @var LoggerInterface
     */
    private $_logger;

    /**
     * __construct
     *
     * @param DataHelper $dataHelper
     * @param QuoteService $quoteService
     * @param LoggerInterface $logger
     */
    public function __construct(
        DataHelper $dataHelper,
        QuoteService $quoteService,
        LoggerInterface $logger
    ) {
        $this->_dataHelper   = $dataHelper;
        $this->_quoteService = $quoteService;
        $this->_logger       = $logger;
    }

    /**
     * AroundGetItemData
     *
     * @param MagentoDefaultItem $subject
     * @param \Closure $proceed
     * @param Item $item
     * @return array
     */
    public function aroundGetItemData(
        MagentoDefaultItem $subject,
        \Closure $proceed,
        Item $item
    ) {
        $data = $proceed($item);
        if (!$this->_dataHelper->isShippingActive()) {
            return $data;
        }

        $additionalData = [];

        try {
            //$product        = $item->getProduct();
            //$servicesHtml   = $this->_quoteService->setItem($item)->setTemplate("Nextouch_GestLogis::cart/item/services.phtml")->toHtml();
            //$additionalData = ["servicesHtml" => $servicesHtml];
        } catch (\Exception $exception) {
            $this->_logger->debug($exception->getMessage());
        } catch (\Error $error) {
            $this->_logger->debug($error->getMessage());
        }

        return array_merge($data, $additionalData);
    }
}

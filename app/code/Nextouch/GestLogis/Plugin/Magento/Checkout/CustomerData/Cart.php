<?php

declare(strict_types=1);

namespace Nextouch\GestLogis\Plugin\Magento\Checkout\CustomerData;

use Magento\Checkout\CustomerData\Cart as MagentoCart;
use Magento\Checkout\Model\Session;
use Magento\Checkout\Helper\Data as CheckoutHelper;
use Magento\Tax\Block\Item\Price\Renderer;
use Psr\Log\LoggerInterface;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;

class Cart
{
    /**
     * @var Session
     */
    private $_checkoutSession;

    /**
     * @var CheckoutHelper
     */
    private $_checkoutHelper;

    /**
     * @var Renderer
     */
    private $_itemPriceRenderer;

    /**
     * @var LoggerInterface
     */
    private $_logger;

    /**
     * @var DataHelper
     */
    private $_dataHelper;

    /**
     * @var Quote|null
     */
    protected $quote = null;

    /**
     * @var array|null
     */
    protected $totals = null;

    /**
     * __construct
     *
     * @param Session $checkoutSession
     * @param CheckoutHelper $checkoutHelper
     * @param Renderer $itemPriceRenderer
     * @param LoggerInterface $logger
     * @param DataHelper $dataHelper
     */
    public function __construct(
        Session $checkoutSession,
        CheckoutHelper $checkoutHelper,
        Renderer $itemPriceRenderer,
        LoggerInterface $logger,
        DataHelper $dataHelper
    ) {
        $this->_checkoutSession   = $checkoutSession;
        $this->_checkoutHelper    = $checkoutHelper;
        $this->_itemPriceRenderer = $itemPriceRenderer;
        $this->_logger            = $logger;
        $this->_dataHelper        = $dataHelper;
    }

    /**
     * AfterGetSectionData
     *
     * @param MagentoCart $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetSectionData(MagentoCart $subject, $result)
    {
        try {
            $result['subtotal_incl_tax'] = $this->_checkoutHelper->formatPrice($this->getSubtotalInclTax());
            $result['subtotal_excl_tax'] = $this->_checkoutHelper->formatPrice($this->getSubtotalExclTax());

            $items = $this->getQuote()->getAllVisibleItems();
            if (is_array($result['items'])) {
                foreach ($result['items'] as $key => $itemAsArray) {
                    if ($item = $this->findItemById($itemAsArray['item_id'], $items)) {
                        $this->_itemPriceRenderer->setItem($item);
                        $this->_itemPriceRenderer->setTemplate('Nextouch_GestLogis::checkout/cart/item/price/sidebar.phtml');
                        $result['items'][$key]['product_price'] = $this->_itemPriceRenderer->toHtml();
                        if ($this->_itemPriceRenderer->displayPriceExclTax()) {
                            $result['items'][$key]['product_price_value'] = $item->getCalculationPrice();
                        } elseif ($this->_itemPriceRenderer->displayPriceInclTax()) {
                            $result['items'][$key]['product_price_value'] = $item->getPriceInclTax();
                        } elseif ($this->_itemPriceRenderer->displayBothPrices()) {
                            //unset product price value in case price already has been set as scalar value.
                            unset($result['items'][$key]['product_price_value']);
                            $result['items'][$key]['product_price_value']['incl_tax'] = $item->getPriceInclTax();
                            $result['items'][$key]['product_price_value']['excl_tax'] = $item->getCalculationPrice();
                        }
                    }
                }
            }

            // Code to have grand_total at minicart top
            $totals = $this->_checkoutSession->getQuote()->getTotals();
            if (isset($totals['grand_total'])) {
                $grandTotal   = $totals['grand_total'];
                $price        = $grandTotal->getValueInclTax() ?: $grandTotal->getValue();
                $updatedPrice = $price;

                if ($this->_dataHelper->isShippingActive()) {
                    $gestlogisPrice = $this->_dataHelper->getGestLogisShipPrice();
                    $updatedPrice   = $price + $gestlogisPrice;
                }

                $result['grand_total'] = $this->_checkoutHelper->formatPrice($updatedPrice);
            }
        } catch (LocalizedException $e) {
            $this->_logger->error($e->getMessage());
        }

        return $result;
    }

    /**
     * Get subtotal, including tax
     *
     * @return float
     */
    protected function getSubtotalInclTax()
    {
        $subtotal = 0;
        $totals = $this->getTotals();
        if (isset($totals['subtotal'])) {
            $subtotal = $totals['subtotal']->getValueInclTax() ?: $totals['subtotal']->getValue();
        }
        return $subtotal;
    }

    /**
     * Get subtotal, excluding tax
     *
     * @return float
     */
    protected function getSubtotalExclTax()
    {
        $subtotal = 0;
        $totals = $this->getTotals();
        if (isset($totals['subtotal'])) {
            $subtotal = $totals['subtotal']->getValueExclTax() ?: $totals['subtotal']->getValue();
        }
        return $subtotal;
    }

    /**
     * Get totals
     *
     * @return array
     */
    public function getTotals()
    {
        // TODO: TODO: MAGETWO-34824 duplicate \Magento\Checkout\CustomerData\Cart::getSectionData
        if (empty($this->totals)) {
            $this->totals = $this->getQuote()->getTotals();
        }
        return $this->totals;
    }

    /**
     * Get active quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (null === $this->quote) {
            $this->quote = $this->_checkoutSession->getQuote();
        }
        return $this->quote;
    }

    /**
     * Find item by id in items haystack
     *
     * @param int $id
     * @param array $itemsHaystack
     * @return \Magento\Quote\Model\Quote\Item | bool
     */
    protected function findItemById($id, $itemsHaystack)
    {
        if (is_array($itemsHaystack)) {
            foreach ($itemsHaystack as $item) {
                /** @var $item \Magento\Quote\Model\Quote\Item */
                if ((int)$item->getItemId() == $id) {
                    return $item;
                }
            }
        }

        return false;
    }
}

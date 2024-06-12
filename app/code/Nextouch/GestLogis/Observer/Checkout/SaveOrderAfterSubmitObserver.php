<?php

namespace Nextouch\GestLogis\Observer\Checkout;

use Magento\Framework\Event\ObserverInterface;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Magento\Framework\Event\Observer;

class SaveOrderAfterSubmitObserver implements ObserverInterface
{
    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * __construct
     *
     * @param DataHelper $dataHelper
     */
    public function __construct(
        DataHelper $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
    }

    /**
     * Execute
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            $shippingActive = $this->_dataHelper->isShippingActive();
            if ($shippingActive) {
                $order = $observer->getEvent()->getOrder();

                if ($order) {
                    $this->executeOrderShippingService($order);
                }
            }
        } catch (\Exception $exception) {
            // Do Nothing.
        } catch (\Error $error) {
            // Do Nothing.
        }
    }

    /**
     * ExecuteOrderShippingService
     *
     * @param object $order
     * @return void
     */
    public function executeOrderShippingService($order)
    {
        if ($order) {
            $items = $order->getItems();

            if (count($items)) {
                foreach ($items as $item) {
                    $quoteItemId = (int) $item->getQuoteItemId();
                    $itemId      = (int) $item->getItemId();

                    $this->_dataHelper->saveOrderShippingService($itemId, $quoteItemId);
                }
            }
        }
    }
}

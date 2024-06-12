<?php

namespace Nextouch\GestLogis\Observer\Cart;

use Magento\Framework\Event\ObserverInterface;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Magento\Quote\Api\CartRepositoryInterface as QuoteRepository;
use Magento\Framework\Event\Observer;

class TruncateCart implements ObserverInterface
{
    /**
     * @var DataHelper
     */
    private $_dataHelper;

    /**
     * @var QuoteRepository
     */
    private $_quoteRepository;

    /**
     * __construct
     *
     * @param DataHelper $dataHelper
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        DataHelper $dataHelper,
        QuoteRepository $quoteRepository
    ) {
        $this->_dataHelper      = $dataHelper;
        $this->_quoteRepository = $quoteRepository;
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

                $checkoutSession = $this->_dataHelper->getCheckoutSession();
                $quoteId         = $checkoutSession->getQuote()->getId();

                $activeQuote = $this->_quoteRepository->get($quoteId);
                $activeQuote->setIsActive(0);
                $this->_quoteRepository->save($activeQuote);

                $checkoutSession->replaceQuote($quoteId);
            }
        } catch (\Exception $exception) {
            // Do Nothing.
        } catch (\Error $error) {
            // Do Nothing.
        }
    }
}

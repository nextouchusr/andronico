<?php

namespace Nextouch\GestLogis\Plugin\Magento\Checkout\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;

class Session
{
    /**
     * Quote instance
     *
     * @var Quote
     */
    protected $_quote;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_customerSession = $customerSession;
        $this->_eventManager = $eventManager;
        $this->quoteRepository = $quoteRepository;
        $this->quoteFactory = $quoteFactory;
        $this->_storeManager = $storeManager;
    }

    public function aroundLoadCustomerQuote(
        \Magento\Checkout\Model\Session $subject,
        callable $proceed
    ) {

        if (!$this->_customerSession->getCustomerId()) {
            return $this;
        }

        $this->_eventManager->dispatch('load_customer_quote_before', ['checkout_session' => $this]);

        try {
            $customerQuote = $this->quoteRepository->getForCustomer($this->_customerSession->getCustomerId());
        } catch (NoSuchEntityException $e) {
            $customerQuote = $this->quoteFactory->create();
        }
        $customerQuote->setStoreId($this->_storeManager->getStore()->getId());

        if ($customerQuote->getId() && $subject->getQuoteId() != $customerQuote->getId()) {

            if ($subject->getQuoteId()) {
                $quote = $subject->getQuote();
                $quote->setCustomerIsGuest(0);
                $this->quoteRepository->save(
                    $customerQuote->merge($quote)->collectTotals()
                );
                $newQuote = $this->quoteRepository->get($customerQuote->getId());
                $this->quoteRepository->save(
                    $newQuote->collectTotals()
                );
                $customerQuote = $newQuote;
                $this->_eventManager->dispatch('load_customer_quote_merge_after', ['quote' => $customerQuote, 'source' => $quote]);
            }

            $subject->setQuoteId($customerQuote->getId());

            if ($this->_quote) {
                $this->quoteRepository->delete($this->_quote);
            }
            $this->_quote = $customerQuote;
        } else {

            $subject->getQuote()->getBillingAddress();
            $subject->getQuote()->getShippingAddress();
            $subject->getQuote()->setCustomer($this->_customerSession->getCustomerDataObject())
                ->setCustomerIsGuest(0)
                ->setTotalsCollectedFlag(false)
                ->collectTotals();
            $this->quoteRepository->save($subject->getQuote());
        }
        return $subject;
    }
}
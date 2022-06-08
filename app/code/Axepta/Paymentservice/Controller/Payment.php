<?php
/**
 * Copyright © Axepta S.p.a. All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Controller;

use Magento\Customer\Api\Data\GroupInterfaceFactory;
use Magento\Payment\Block\Transparent\Iframe;
use Magento\Sales\Model\Order;
use Magento\Setup\Exception;

/*
**
 * Payment Controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */

abstract class Payment extends \Magento\Framework\App\Action\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Axepta\Paymentservice\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    private $orderCollectionFactory;

    private $storeManager;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Axepta\Paymentservice\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Axepta\Paymentservice\Helper\Data $helper
    ) {
        parent::__construct($context);

        $this->coreRegistry = $coreRegistry;
        $this->quoteRepository = $quoteRepository;
        $this->helper = $helper;
        $this->storeManager = $this->_objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $this->scopeConfig = $this->_objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class);
    }

    private function isSalesEmailOrderEnabled()
    {
        $storeId = $this->storeManager->getStore()->getStoreId();
        $enabled = $this->scopeConfig->isSetFlag(
            'sales_email/order/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        return $enabled;
    }

    /**
     * @return \Magento\Checkout\Model\Session
     */
    protected function getCheckout()
    {
        return $this->_objectManager->get(\Magento\Checkout\Model\Session::class);
    }

    /**
     * Get session model
     *
     * @return \Axepta\Paymentservice\Model\Session
     */
    protected function getPaymentSession()
    {
        return $this->_objectManager->get(\Axepta\Paymentservice\Model\Session::class);
    }

    protected function getCustomerSession()
    {
        return $this->_objectManager->get(\Magento\Customer\Model\Session::class);
    }

    protected function prepareGuestQuote()
    {
        $quote = $this->getCheckout()->getQuote();
        $quote->setCustomerId(null)
            ->setCheckoutMethod(\Magento\Quote\Model\QuoteManagement::METHOD_GUEST)
            ->setCustomerEmail($quote->getBillingAddress()->getEmail())
            ->setCustomerIsGuest(true)
            ->setCustomerGroupId(0);
        return $this;
    }

    /**
     * @param string $area
     * @return \Magento\Framework\App\ResponseInterface
     */
    protected function _responseAction($area = 'frontend')
    {
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $restURLParams = explode('/', $urlInterface->getCurrentUrl());
        $quoteID = (int) end($restURLParams);

        $quote = $this->getCheckout()->getQuote();

        if (!$quote->getId()) {
            return $this->_redirect('checkout/cart', ['_secure' => true]);
        }

        $quoteRepository = $this->_objectManager->get('\Magento\Quote\Api\CartRepositoryInterface');
        $quoteObject = $quoteRepository->get($quote->getId());
        $cartManager = $this->_objectManager->create(\Magento\Quote\Api\CartManagementInterface::class);

        if (! $this->getCustomerSession()->isLoggedIn()) {
            $this->prepareGuestQuote();
        }

        try {
            $response = $this->helper->verify($quoteObject);
            if ($response['error'] === false) {
                // Get the order
                $orderCollectionFactory = $this->_objectManager->get(\Magento\Sales\Model\ResourceModel\Order\CollectionFactory::class);
                $orderCollection = $orderCollectionFactory->create()->addFieldToSelect("*")
                    ->addFieldToFilter('customer_id', $this->getCustomerSession()->getId())
                    ->addFieldToFilter('quote_id', $quoteID)->toArray();

                $orderIncrementId = ($orderCollection && isset($orderCollection['totalRecords']) && $orderCollection['totalRecords']>0) ? $orderCollection['items'][0]['increment_id'] : null;

                $successText = $orderIncrementId ? sprintf('Payment has been successful. Order number is #%s', $orderIncrementId) : 'Payment has been successful.';

                $this->messageManager->addSuccessMessage(__($successText));
                $this->helper->log('[ACTION] [REDIRECT-SUCCESS]: ' . $response['message']);
                return $this->_redirect('checkout/onepage/success', ['_secure' => true]);
            } else {
               //$this->logFailedPaymentAndCancelOrder($quoteObject, $cartManager, $quote, $response['message']);
                $this->messageManager->addErrorMessage(__('Error with your payment:  ' . $response['message']));
                return $this->_redirect('checkout/cart', ['_secure' => true]);
            }
        } catch (\Exception $exception) {
            $this->helper->log('[ACTION] [REDIRECT-ERROR]: ' . $exception->getMessage());
            $this->helper->log($exception->getMessage() . ' -:- Code: ' . $exception->getCode());
            $this->messageManager->addErrorMessage(__('Error with your payment.Please try again later!' . $exception->getMessage()));
            //$this->logFailedPaymentAndCancelOrder($quoteObject, $cartManager, $quote, $exception->getMessage());
            $this->helper->log('[REDIRECT] [ERROR]');
            return $this->_redirect('checkout/cart', ['_secure' => true]);
        }
    }

    protected function isAlreadyOrdered(int $quoteID = null)
    {
        $orderCollectionFactory = $this->_objectManager->get(\Magento\Sales\Model\ResourceModel\Order\CollectionFactory::class);

        $order = $orderCollectionFactory->create()->addFieldToSelect("*")
            ->addFieldToFilter('customer_id', $this->getCustomerSession()->getId())
            ->addFieldToFilter('quote_id', $quoteID)->toArray();
        if ($order['totalRecords'] !== 0) {
            $quoteRepository = $this->_objectManager->get('\Magento\Quote\Api\CartRepositoryInterface');
            $quoteObject = $quoteRepository->get($quoteID);
            $quoteObject->delete();
            return true; // order is already created (e.g previous callback call)
        }
        return false;
    }

    protected function callbackAction($postData)
    {
        if (!$postData) {
            exit;
        }

        // Recupero l'id dell'ordine
        $requestUri = explode('/', $_SERVER['REQUEST_URI']);
        $orderId = null;
        while ($orderId == null) {
            $temp = array_pop($requestUri);
            if ($temp != '') {
                $orderId = $temp;
            }
        }

        $modelOrder = $this->_objectManager->create('Magento\Sales\Api\Data\OrderInterface')->loadByIncrementId($orderId);
        
        $order = $this->_objectManager->create('Magento\Sales\Api\OrderRepositoryInterface')->get($modelOrder->getId());
        $this->saveOrderCallbackAction($order, $postData);
    }

    /** This action is responsible for saving orders triggered by callback
     * server-to-server communication between BNL and merchant's server!
     * @param $quote
     * @param $postData
     */
    private function saveOrderCallbackAction($order, $postData)
    {
   
        $order->set($postData);

        try {
            $response = $this->helper->verify($order);
            if ($response['error'] === false) {
                $this->saveOrder($order, $response);
            } else {
                $this->logFailedPaymentAndCancelOrder($order, $response['message'], $response);
            }
            $this->helper->log('Callback:gateway response: '. $response['message'] . ' -:- Code: ' . $response['returnCode']);
        } catch (\Exception $exception) {
            $this->helper->log('Callback:'. $exception->getMessage() . ' -:- Code: ' . $exception->getCode());
            // $this->logFailedPaymentAndCancelOrder($quoteObject, $cartManager, $quote, $exception->getMessage());
        }
    }

    private function saveOrder($order, $response)
    {
        $order->getPayment()->setAdditionalInformation('return_code', $response['returnCode']);
        $order->getPayment()->setAdditionalInformation('transaction_id', $response['tranID']);
        $order->getPayment()->setAdditionalInformation('payment_id', $response['paymentID']);
        (isset($response['XID'])) ? $order->getPayment()->setAdditionalInformation('XID', $response['XID']) : '';

        $payment = $order->getPayment();
        switch ($this->helper->getConfig('payment_action')) {
            case 'authorize':
                $order->addStatusHistoryComment(__('Payment authorized. Use specific function to capture funds.'));
                $payment->setAdditionalInformation('state', 'authorized');

                $totalDue = $order->getTotalDue();
                $baseTotalDue = $order->getBaseTotalDue();
                // Se si attiva setIsTransactionPending, disabilitare le successive 2 righe $orderState e $order, perchè
                // l'authorize() applica lo stato PENDING_REVIEW
                $payment->setIsTransactionPending(false);
                $payment->authorize(true, $baseTotalDue);
                // base amount will be set inside
                // $orderState = Order::STATE_PENDING_PAYMENT;
                // $order->setState($orderState)->setStatus(Order::STATE_PENDING_PAYMENT);

                if ($this->isSalesEmailOrderEnabled()) {
                    $orderSender  = $this->_objectManager->create(\Magento\Sales\Model\Order\Email\Sender\OrderSender::class);
                    $result = $orderSender->send($order);
                }

                break;
            case 'authorize_capture':
                $order->addStatusHistoryComment(__('Payment processing.'));
                $payment->setAdditionalInformation('state', 'processing');

                // Axepta code commented, because the order status that we need is PAID[PROCESSING]
                // $orderState = Order::STATE_PROCESSING;
                // $order->setState($orderState)->setStatus(Order::STATE_PROCESSING);

                $orderState = Order::STATE_PROCESSING;
                $order->setState($orderState)->setStatus('paid');

                $totalDue = $order->getTotalDue();
                $baseTotalDue = $order->getBaseTotalDue();
                $payment->setAmountAuthorized($totalDue);
                $payment->setBaseAmountAuthorized($baseTotalDue);
                $payment->capture(null);

                if ($this->isSalesEmailOrderEnabled()) {
                    $orderSender  = $this->_objectManager->create(\Magento\Sales\Model\Order\Email\Sender\OrderSender::class);
                    $result = $orderSender->send($order);
                }

                break;
            default:
                $order->setState(Order::STATE_HOLDED)->setStatus(Order::STATE_HOLDED);
                break;
        }
        (isset($response['shopID'])) ? $order->addStatusHistoryComment(__('Shop ID: ' . $response['shopID'])) : $order->addStatusHistoryComment(__('Shop ID: ' .  'SHOP ID NOT FOUND'));
        $payment->setAdditionalInformation('transaction_id', $response['tranID']);
        $payment->setAdditionalInformation('return_code', $response['returnCode']);
        $payment->save();
        $order->save();

        $lastQuoteId = $this->getCheckout()->getLastQuoteId();
        if ($lastQuoteId) {
            $quoteRepository = $this->_objectManager->get('\Magento\Quote\Api\CartRepositoryInterface');
            $quoteObject = $quoteRepository->get($lastQuoteId);
            $quoteObject->setIsActive(true)->save();
        }

        $checkout = $this->getCheckout();
        $checkout->clearQuote();
        $checkout->clearStorage();
        $checkout->restoreQuote();

        return $order;
    }


    private function logFailedPaymentAndCancelOrder($order, $comment, $response)
    {

        $payment = $order->getPayment();
        $payment->save();
        $state = 'canceled';
        $status = 'canceled';
        $order->cancel();
        $order->addStatusHistoryComment(__('Payment failed. Reason: ' . $comment));
        $order->setState($state);
        $order->setStatus($status);
        $order->save();

        $quote = $this->getCheckout()->getQuote();
        $quote->setIsActive(true)->save();
    }
}

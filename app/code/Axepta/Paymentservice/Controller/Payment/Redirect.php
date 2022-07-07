<?php

/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Controller\Payment;

use Axepta\Paymentservice\Model\Ui\ConfigProvider;
use Magento\Checkout\Exception;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order;

/**
 * Class Redirect
 */
class Redirect extends \Axepta\Paymentservice\Controller\Payment
{
    /**
     * @var CartManagementInterface
     */
    private $cartManagement;
    private $orderCollectionFactory;
    private $paymentCollectionFactory;
    private $transactionBuilder;

    const AXEPTA_PG = 'axepta';

    private $resultJsonFactory;

    /**
     * Redirect constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Axepta\Paymentservice\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Axepta\Paymentservice\Helper\Data $helper
    ) {
        parent::__construct($context, $coreRegistry, $quoteRepository, $helper);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->orderCollectionFactory = $this->_objectManager->get(\Magento\Quote\Model\ResourceModel\Quote\CollectionFactory::class);
        $this->paymentCollectionFactory = $this->_objectManager->get(\Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory::class);
        $this->transactionBuilder = $this->_objectManager->get(\Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface::class);
        $this->jsonResultFactory = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class);
        $rawFactory = $this->_objectManager->create(\Magento\Framework\Controller\Result\RawFactory::class);
        $helper = $this->_objectManager->get('\Axepta\Paymentservice\Helper\Data');

        $cartManager = $this->_objectManager->create(\Magento\Quote\Api\CartManagementInterface::class);

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $quoteRepository = $this->_objectManager->get('\Magento\Quote\Api\CartRepositoryInterface');

        if (!$this->getCheckout()->getQuote()->getId()) {
            $this->messageManager->addErrorMessage(__('No products found in cart!'));
            return $this->_redirect('checkout/cart', ['_secure' => true]);
        }

        $quoteRepository = $this->_objectManager->get('\Magento\Quote\Api\CartRepositoryInterface');
        $quote = $this->getCheckout()->getQuote();

        $nextOrderId = $quote->reserveOrderId()->getReservedOrderId();
        $quote->reserveOrderId()->save();
        $this->getPaymentSession()->setQuoteId($quote->getId());
        $quoteObject = $quoteRepository->get($quote->getId());

        if (!$this->getCustomerSession()->isLoggedIn()) {
            $this->prepareGuestQuote();
        }

        $args = $quote->getPayment()->getAdditionalInformation();

        $uniqid = \sha1(microtime());

        if (!($quoteObject->getPayment()->getAdditionalInformation('order_reference'))) {
            $response = $helper->init($quoteObject, $args);
            if ($response && count($response)) {
                $quoteObject->getPayment()->setMethod(ConfigProvider::CODE);
                $quoteObject->getPayment()->setAdditionalInformation('order_reference', $nextOrderId);
                $quoteObject->getPayment()->setAdditionalInformation('shopID', $uniqid);
                $quoteObject->getPayment()->setAdditionalInformation('transaction_id', $response['paymentID']);
                $quoteObject->getPayment()->setAdditionalInformation('payment_id', $response['paymentID']);
                $quoteObject->getPayment()->setAdditionalInformation('method', $helper->getMethod());
                $quoteObject->getPayment()->setAdditionalInformation('redirect_url', $response['redirectURL']);
                $quoteObject->save();

                $orderId = $cartManager->placeOrder($quoteObject->getId());
                $order = $this->_objectManager->create('Magento\Sales\Api\OrderRepositoryInterface')->get($orderId);
                $order->getPayment()->setAdditionalInformation('order_reference', $nextOrderId);
                $order->getPayment()->setAdditionalInformation('shopID', $uniqid);
                $order->getPayment()->setAdditionalInformation('transaction_id', $response['paymentID']);
                $order->getPayment()->setAdditionalInformation('payment_id', $response['paymentID']);
                $order->getPayment()->setAdditionalInformation('method', $helper->getMethod());
                $order->getPayment()->setAdditionalInformation('redirect_url', $response['redirectURL']);
                $order->getPayment()->save();
                $order->save();

                // Riattivo il carrello
                $quoteObject->setIsActive(true)->save();

                if ($helper->getMethod() == self::AXEPTA_PG) {
                    $result = $rawFactory->create();
                    return  $result->setContents($response['paymentID']);
                }
                return $this->_redirect($response['redirectURL']);
            }
        }

        $orderId = $cartManager->placeOrder($quote->getId());
        $order = $this->_objectManager->create('Magento\Sales\Api\OrderRepositoryInterface')->get($orderId);
        $payment = $order->getPayment();
        $payment->save();

      

        return $this->_redirect($quoteObject->getPayment()->getAdditionalInformation('redirect_url'));
    }
}

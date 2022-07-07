<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17.10.18
 * Time: 10:58
 */

namespace Axepta\Paymentservice\Model;

use Magento\Framework\DataObject;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\Method\TransparentInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Payment\Model\Method\ConfigInterface;

class TestPayment extends Axepta
{

    function init($params = [])
    {
        // TODO: Implement init() method.
    }

    function verify($params = [])
    {
        die('here verify');
    }

    /**
     * Retrieve payment method code
     *
     * @return string
     *
     */
    public function getCode()
    {
        // TODO: Implement getCode() method.
    }

    /**
     * Retrieve block type for method form generation
     *
     * @return string
     *
     * @deprecated 100.0.2
     */
    public function getFormBlockType()
    {
        // TODO: Implement getFormBlockType() method.
    }

    /**
     * Retrieve payment method title
     *
     * @return string
     *
     */
    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * Store id setter
     * @param int $storeId
     * @return void
     */
    public function setStore($storeId)
    {
        // TODO: Implement setStore() method.
    }

    /**
     * Store id getter
     * @return int
     */
    public function getStore()
    {
        // TODO: Implement getStore() method.
    }

    /**
     * Check order availability
     *
     * @return bool
     *
     */
    public function canOrder()
    {
        // TODO: Implement canOrder() method.
    }

    /**
     * Check authorize availability
     *
     * @return bool
     *
     */
    public function canAuthorize()
    {
        // TODO: Implement canAuthorize() method.
    }

    /**
     * Check capture availability
     *
     * @return bool
     *
     */
    public function canCapture()
    {
        // TODO: Implement canCapture() method.
    }

    /**
     * Check partial capture availability
     *
     * @return bool
     *
     */
    public function canCapturePartial()
    {
        // TODO: Implement canCapturePartial() method.
    }

    /**
     * Check whether capture can be performed once and no further capture possible
     *
     * @return bool
     *
     */
    public function canCaptureOnce()
    {
        // TODO: Implement canCaptureOnce() method.
    }

    /**
     * Check refund availability
     *
     * @return bool
     *
     */
    public function canRefund()
    {
        // TODO: Implement canRefund() method.
    }

    /**
     * Check partial refund availability for invoice
     *
     * @return bool
     *
     */
    public function canRefundPartialPerInvoice()
    {
        // TODO: Implement canRefundPartialPerInvoice() method.
    }

    /**
     * Check void availability
     * @return bool
     *
     */
    public function canVoid()
    {
        // TODO: Implement canVoid() method.
    }

    /**
     * Using internal pages for input payment data
     * Can be used in admin
     *
     * @return bool
     */
    public function canUseInternal()
    {
        // TODO: Implement canUseInternal() method.
    }

    /**
     * Can be used in regular checkout
     *
     * @return bool
     */
    public function canUseCheckout()
    {
        // TODO: Implement canUseCheckout() method.
    }

    /**
     * Can be edit order (renew order)
     *
     * @return bool
     *
     */
    public function canEdit()
    {
        // TODO: Implement canEdit() method.
    }

    /**
     * Check fetch transaction info availability
     *
     * @return bool
     *
     */
    public function canFetchTransactionInfo()
    {
        // TODO: Implement canFetchTransactionInfo() method.
    }

    /**
     * Fetch transaction info
     *
     * @param InfoInterface $payment
     * @param string $transactionId
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     */
    public function fetchTransactionInfo(InfoInterface $payment, $transactionId)
    {
        // TODO: Implement fetchTransactionInfo() method.
    }

    /**
     * Retrieve payment system relation flag
     *
     * @return bool
     *
     */
    public function isGateway()
    {
        // TODO: Implement isGateway() method.
    }

    /**
     * Retrieve payment method online/offline flag
     *
     * @return bool
     *
     */
    public function isOffline()
    {
        // TODO: Implement isOffline() method.
    }

    /**
     * Flag if we need to run payment initialize while order place
     *
     * @return bool
     *
     */
    public function isInitializeNeeded()
    {
        // TODO: Implement isInitializeNeeded() method.
    }

    /**
     * To check billing country is allowed for the payment method
     *
     * @param string $country
     * @return bool
     */
    public function canUseForCountry($country)
    {
        // TODO: Implement canUseForCountry() method.
    }

    /**
     * Check method for processing with base currency
     *
     * @param string $currencyCode
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function canUseForCurrency($currencyCode)
    {
        // TODO: Implement canUseForCurrency() method.
    }

    /**
     * Retrieve block type for display method information
     *
     * @return string
     *
     * @deprecated 100.0.2
     */
    public function getInfoBlockType()
    {
        // TODO: Implement getInfoBlockType() method.
    }

    /**
     * Retrieve payment information model object
     *
     * @return InfoInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @deprecated 100.0.2
     */
    public function getInfoInstance()
    {
        // TODO: Implement getInfoInstance() method.
    }

    /**
     * Retrieve payment information model object
     *
     * @param InfoInterface $info
     * @return void
     *
     * @deprecated 100.0.2
     */
    public function setInfoInstance(InfoInterface $info)
    {
        // TODO: Implement setInfoInstance() method.
    }

    /**
     * Validate payment method information object
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     */
    public function validate()
    {
        // TODO: Implement validate() method.
    }

    /**
     * Order payment method
     *
     * @param InfoInterface $payment
     * @param float $amount
     * @return $this
     *
     */
    public function order(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        // TODO: Implement order() method.
    }

    /**
     * Authorize payment method
     *
     * @param InfoInterface $payment
     * @param float $amount
     * @return $this
     *
     */
    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        // TODO: Implement authorize() method.
    }

    /**
     * Capture payment method
     *
     * @param InfoInterface $payment
     * @param float $amount
     * @return $this
     *
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        // TODO: Implement capture() method.
    }

    /**
     * Refund specified amount for payment
     *
     * @param InfoInterface $payment
     * @param float $amount
     * @return $this
     *
     */
    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        // TODO: Implement refund() method.
    }

    /**
     * Cancel payment method
     *
     * @param InfoInterface $payment
     * @return $this
     *
     */
    public function cancel(\Magento\Payment\Model\InfoInterface $payment)
    {
        // TODO: Implement cancel() method.
    }

    /**
     * Void payment method
     *
     * @param InfoInterface $payment
     * @return $this
     *
     */
    public function void(\Magento\Payment\Model\InfoInterface $payment)
    {
        // TODO: Implement void() method.
    }

    /**
     * Whether this method can accept or deny payment
     * @return bool
     *
     */
    public function canReviewPayment()
    {
        // TODO: Implement canReviewPayment() method.
    }

    /**
     * Attempt to accept a payment that us under review
     *
     * @param InfoInterface $payment
     * @return false
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     */
    public function acceptPayment(InfoInterface $payment)
    {
        // TODO: Implement acceptPayment() method.
    }

    /**
     * Attempt to deny a payment that us under review
     *
     * @param InfoInterface $payment
     * @return false
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     */
    public function denyPayment(InfoInterface $payment)
    {
        // TODO: Implement denyPayment() method.
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param int|string|null|\Magento\Store\Model\Store $storeId
     *
     * @return mixed
     */
    public function getConfigData($field, $storeId = null)
    {
        // TODO: Implement getConfigData() method.
    }

    /**
     * Assign data to info model instance
     *
     * @param DataObject $data
     * @return $this
     *
     */
    public function assignData(DataObject $data)
    {
        // TODO: Implement assignData() method.
    }

    /**
     * Check whether payment method can be used
     *
     * @param CartInterface|null $quote
     * @return bool
     *
     */
    public function isAvailable(CartInterface $quote = null)
    {
        // TODO: Implement isAvailable() method.
    }

    /**
     * Is active
     *
     * @param int|null $storeId
     * @return bool
     *
     */
    public function isActive($storeId = null)
    {
        // TODO: Implement isActive() method.
    }

    /**
     * Method that will be executed instead of authorize or capture
     * if flag isInitializeNeeded set to true
     *
     * @param string $paymentAction
     * @param object $stateObject
     *
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     */
    public function initialize($paymentAction, $stateObject)
    {
        // TODO: Implement initialize() method.
    }

    /**
     * Get config payment action url
     * Used to universalize payment actions when processing payment place
     *
     * @return string
     *
     */
    public function getConfigPaymentAction()
    {
        // TODO: Implement getConfigPaymentAction() method.
    }
}

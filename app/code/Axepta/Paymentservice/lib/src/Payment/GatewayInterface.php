<?php
namespace Payment;

/**
 *
 * @author Sendabox
 */
interface GatewayInterface
{
    /**
     *
     * Transaction initializer. Create the Redirect URL.
     *
     * @param array $params
     * @return array|object
     */
    public function init(array $params);
    /**
     *
     * Verify transaction. Receive only the status of the specific transaction.
     *
     * @param array $params
     * @return array|object
     */
    public function verify(array $params);
    /**
     *
     * Transaction confirmation.
     * Transfer a specific amount from an authorized transaction
     *
     * @param array $params
     * @return array|object
     */
    public function confirm(array $params);
    /**
     *
     * Refund transaction. Return a specific amount back to buyer.
     *
     * @param array $params
     * @return array|object
     */
    public function refund(array $params);
    /**
     *
     * Cancel pending transaction. Return a specific amount back to buyer.
     *
     * @param array $params
     * @return array|object
     */
    public function cancel(array $params);
    /**
     *
     * Return all the possible payment instruments
     *
     * @param
     * @return array|object
     */
    public function getPaymentInstruments();
    /**
     *
     * Return all the possible transaction types
     *
     * @param
     * @return array|object
     */
    public function getTransactionTypes();
    /**
     *
     * Return all the possible cheout types
     *
     * @param
     * @return array|object
     */
    public function getCheckoutTypes();
}

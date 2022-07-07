<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class AuthorizationRequest implements BuilderInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * Builds ENV request
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment'])
            || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /** @var PaymentDataObjectInterface $payment */
        $paymentDO = $buildSubject['payment'];
        $payment = $paymentDO->getPayment();
        $order = $paymentDO->getOrder();
        $paymentID = ($payment->getAdditionalInformation('payment_id')) ? $payment->getAdditionalInformation('payment_id') : '12345677';
        $tranID = ($payment->getAdditionalInformation('transaction_id')) ? $payment->getAdditionalInformation('transaction_id') : '';
        $returnCode = ($payment->getAdditionalInformation('return_code')) ? $payment->getAdditionalInformation('return_code') : '';
        $orderReference = ($payment->getAdditionalInformation('order_reference')) ? $payment->getAdditionalInformation('order_reference') : '12871';
        $method = ($payment->getAdditionalInformation('method')) ? $payment->getAdditionalInformation('method') : null;

        return [
            'orderReference' => $orderReference,
            'paymentID' => $paymentID,
            'tranID' => $tranID,
            'return_code' => $returnCode,
            'code' => '000',
            'method' => '000',
            'status' => 'success',
            'message' => 'success',
            'error' => false,
        ];
    }
}

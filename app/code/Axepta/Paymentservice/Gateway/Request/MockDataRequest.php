<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */
namespace Axepta\Paymentservice\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Axepta\Paymentservice\Gateway\Http\Client\AbstractTransaction;

class MockDataRequest implements BuilderInterface
{
    const FORCE_RESULT = 'FORCE_RESULT';

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

        /** @var PaymentDataObjectInterface $paymentDO */
        $paymentDO = $buildSubject['payment'];
        $payment = $paymentDO->getPayment();

        $transactionResult = $payment->getAdditionalInformation('order_reference');
        $paymentID = $payment->getAdditionalInformation('payment_id');
        return [
            'test-value' => $paymentID
        ];
    }
}

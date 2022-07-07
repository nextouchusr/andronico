<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 24.10.18
 * Time: 13:21
 */

namespace Axepta\Paymentservice\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class SettlementDataBuilder implements BuilderInterface
{

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
        $paymentDO = $buildSubject['payment'];
        $payment = $paymentDO->getPayment();
        $order = $paymentDO->getOrder();

        return  [
            'paymentReference' => $payment->getAdditionalInformation('transaction_id'),
            'amount' => $payment->getAmountAuthorized(),
            'payment_id' => $payment->getAdditionalInformation('payment_id'),
            'order_reference' => $payment->getAdditionalInformation('order_reference'),
            'last_trans_id' => $payment->getLastTransId(),
            'order_id' => $order->getId(),
        ];
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 24.10.18
 * Time: 11:19
 */

namespace Axepta\Paymentservice\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class VoidDataBuilder implements BuilderInterface
{


    /**
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

        return [
            'order_id' => $payment->getOrder()->getId(),
            'transaction_id' => $payment->getLastTransId(),
            'order_reference' => $payment->getAdditionalInformation('order_reference'),
            'payment_id' => $payment->getAdditionalInformation('payment_id'),
            'amount' => $payment->getAmountAuthorized(),
        ];
    }
}

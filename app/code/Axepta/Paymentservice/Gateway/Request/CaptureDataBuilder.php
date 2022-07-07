<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 24.10.18
 * Time: 16:27
 */

namespace Axepta\Paymentservice\Gateway\Request;

use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\Request\BuilderInterface;

class CaptureDataBuilder implements BuilderInterface
{

    const TRANSACTION_ID = 'transaction_id';

    const AMOUNT = 'amount';


    /**
     * @param array $buildSubject
     * @return array
     * @throws LocalizedException
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $buildSubject['payment'];
        $payment = $paymentDO->getPayment();
        $order = $paymentDO->getOrder();

        $transactionId = $payment->getLastTransId();
        if (!$transactionId) {
            throw new LocalizedException(__('No authorization transaction to proceed capture.'));
        }

        return [
            self::TRANSACTION_ID => $transactionId,
            self::AMOUNT => $payment->getAmountAuthorized(),
            'payment_id' => $payment->getAdditionalInformation('payment_id'),
            'order_reference' => $payment->getAdditionalInformation('order_reference'),
            'last_trans_id' => $payment->getLastTransId(),
            'order_id' => $order->getId(),
        ];
    }
}

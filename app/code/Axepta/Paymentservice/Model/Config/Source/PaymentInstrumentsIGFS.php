<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */
namespace Axepta\Paymentservice\Model\Config\Source;

use Axepta\Paymentservice\Helper\Data;
use Magento\Framework\Option\ArrayInterface;

class PaymentInstrumentsIGFS implements ArrayInterface
{
    /**
     * @var Data
     */
    private $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $igfsInstruments = [
            'visa' => 'Visa',
            'mastercard' => 'Mastercard',
            'maestro' => 'Maestro',
            'diners' => 'Diners',
            'americanexpress' => 'American Express',
            'findomestic' => 'Findomestic',
            'masterpass' => 'Masterpass',
            'mybank' => 'MyBank',
            'paypal' => 'PayPal'
        ];
        $paymentInstrumentsIGFS = [];

        //TODO  change with values from Library!!!
        foreach ($this->helper->getPaymentInstrumentsIGFS() as $key => $item) {
            $paymentInstrumentsIGFS[] = [
                'value' => $key,
                'label' => $item,
            ];
        }
        return $paymentInstrumentsIGFS;
    }
}

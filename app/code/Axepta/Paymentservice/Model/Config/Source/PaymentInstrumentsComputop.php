<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */
namespace Axepta\Paymentservice\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Axepta\Paymentservice\Helper\Data;

class PaymentInstrumentsComputop implements ArrayInterface
{

    private $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        /*Test data !!*/
        $paymentInstruments = [
            'visa' => 'Visa',
            'mastercard' => 'Mastercard',
            'maestro' => 'Maestro',
            'americanexpress' => 'American Express',
            'diners' => 'Diners',
            'findomestic' => 'Findomestic',
            'mybank' => 'MyBank',
            'alipay' => 'Alipay',
            'cupay' => 'Chinaunionpay',
            'wechat' => 'WeChat',
            'giropay' => 'Giropay',
            'sofort' => 'Sofort',
            'ideal' => 'Ideal',
            'p24' => 'P24',
            'multibanco' => 'Multibanco',
            'zimpler' => 'Zimpler'
        ];

        $paymentInstrumentsComputop = [];

        //TODO  change with values from Library!!!
        foreach ($this->helper->getPaymentInstrumentsComputop() as $key => $item) {
            $paymentInstrumentsComputop[] = [
                'value' => $key,
                'label' => $item,
            ];
        }
        return $paymentInstrumentsComputop;
    }
}

<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */
namespace Axepta\Paymentservice\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class PaymentMethods implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            [
                'value' => 'axepta',
                'label' => 'Paga con carta di credito',
            ],
        ];
    }
}

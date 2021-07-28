<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */
namespace Axepta\Paymentservice\Model\Config\Source;

use Axepta\Paymentservice\Helper\Data;
use Magento\Framework\Option\ArrayInterface;

class CheckoutTypes implements ArrayInterface
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
        $libChTypes = [
            '1' => 'Checkout BNLP',
            '2' => 'Checkout BNLP con sintesi in web store',
            '3' => 'Checkout BNLP con selezione strumento di pagamento su web store',
        ];

        $checkoutTypes = [];
        foreach ($this->helper->getCheckoutTypes() as $key => $item) {
            $checkoutTypes[] = [
                'value' => $key,
                'label' => $item,
            ];
        }
        return $checkoutTypes;
    }
}

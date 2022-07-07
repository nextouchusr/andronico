<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Model\Adapter;

use Axepta\Paymentservice\Helper\Data;
use Braintree\ClientToken;
use Braintree\Configuration;
use Braintree\CreditCard;
use Braintree\PaymentMethodNonce;
use Braintree\Transaction;
use Magento\Braintree\Gateway\Config\Config;
use Magento\Braintree\Model\Adminhtml\Source\Environment;

/**
 * Class AxeptaAdapter
 * Use \Magento\Braintree\Model\Adapter\AxeptaAdapterFactory to create new instance of adapter.
 * @codeCoverageIgnore
 */
class AxeptaAdapter
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Data
     */
    private $gateway;

    /**
     * @param Data $gateway
     */
    public function __construct(Data $gateway)
    {
        $this->gateway = $gateway;
    }


    /**
     * @param array $attributes
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Setup\Exception
     */
    public function init(array $attributes)
    {
        return $this->gateway->init($attributes, $args = null);
    }

    /**
     * @param array $attributes
     * @return \Braintree\Result\Successful|\Braintree\Result\Error
     */
    public function verify(array $attributes)
    {
        return $this->gateway->adapterVerify($attributes);
    }


    /**
     * @param array $data
     * @return mixed
     */
    public function submitForSettlement(array $data)
    {
        return $this->gateway->confirm($data);
    }


    /**
     * @param array $data
     * @return array
     */
    public function void(array $data)
    {
        return $this->gateway->void($data);
    }


    /**
     * @param array $data
     * @return mixed
     */
    public function refund(array $data)
    {
        return $this->gateway->refund($data);
    }
}

<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */
namespace Axepta\Paymentservice\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'axepta_paymentservice';

    /**
     * redirectUrl
     *
     * @var string
     */
    protected $redirectUrl;

     /**
      * @var \Axepta\Paymentservice\Helper\Data
      */
    protected $helper;

    public function __construct(
        \Magento\Framework\UrlInterface $urlInterface,
        \Axepta\Paymentservice\Helper\Data $helper
    ) {
        $this->redirectUrl = $urlInterface->getUrl('axepta/payment/redirect');
        $this->helper = $helper;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $isActive = filter_var($this->helper->getConfig('active'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        $data = [
            'payment' => [
                self::CODE => [
                    'redirecturl' =>  $this->redirectUrl,
                    'description' =>  $this->helper->getConfig('description'),
                    'axepta_easy_license_token' =>  $this->helper->getConfig('page_license_key_axepta'),
                    'axepta_smart_license_token' =>  $this->helper->getConfig('js_license_key_axepta'),
                    'axepta_sdk_url' => $this->helper->getSdkUrl(),
                    'axepta_api_url' => $this->helper->getApiUrl(),
                ],
            ]
        ];

        if (!$isActive) {
            return $data;
        }

        $data['payment'][self::CODE]['checkout_type'] = $this->helper->getConfig('checkout_type');
        $data['payment'][self::CODE]['method'] =  $this->helper->getMethod();
        return $data;
    }
}

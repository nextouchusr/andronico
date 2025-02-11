<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */
namespace  Axepta\Paymentservice\Gateway\Config;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Helper\SubjectReader;

/**
 * Default implementation of config value handler.
 *
 * This class is designed to be injected into other classes. Inheritance in not recommended.
 *
 * @api
 * @since 100.0.2
 */
class ConfigValueHandler implements \Magento\Payment\Gateway\Config\ValueHandlerInterface
{
    /**
     * @var \Magento\Payment\Gateway\ConfigInterface
     */
    private $configInterface;

    /**
     * @param \Magento\Payment\Gateway\ConfigInterface $configInterface
     */
    public function __construct(
        ConfigInterface $configInterface
    ) {
        $this->configInterface = $configInterface;
    }

    /**
     * Retrieve method configured value
     *
     * @param array $subject
     * @param int|null $storeId
     *
     * @return mixed
     */
    public function handle(array $subject, $storeId = null)
    {
        return $this->configInterface->getValue(SubjectReader::readField($subject), $storeId);
    }
}

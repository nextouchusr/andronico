<?php
declare(strict_types=1);

namespace Nextouch\InStorePayment\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Nextouch\InStorePayment\Helper\InStorePaymentConfig;
use Nextouch\InStorePayment\Model\InStorePayment;

class ConfigProvider implements ConfigProviderInterface
{
    private InStorePaymentConfig $config;

    public function __construct(InStorePaymentConfig $config)
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        if (!$this->config->isActive()) {
            return [];
        }

        return [
            'payment' => [
                InStorePayment::METHOD_CODE => [
                    'instructions' => $this->config->getInstructions(),
                ],
            ],
        ];
    }
}

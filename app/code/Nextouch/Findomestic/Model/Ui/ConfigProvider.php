<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\UrlInterface;
use Nextouch\Findomestic\Helper\FindomesticConfig;
use Nextouch\Findomestic\Model\Findomestic;

class ConfigProvider implements ConfigProviderInterface
{
    protected string $redirectUrl;
    private FindomesticConfig $config;

    public function __construct(UrlInterface $urlInterface, FindomesticConfig $config)
    {
        $this->redirectUrl = $urlInterface->getUrl('findomestic/payment/redirect');
        $this->config = $config;
    }

    public function getConfig(): array
    {
        if (!$this->config->isActive()) {
            return [];
        }

        return [
            'payment' => [
                Findomestic::METHOD_CODE => [
                    'redirect_url' => $this->redirectUrl,
                    'base_path' => $this->config->getBasePath(),
                    'partner_id' => $this->config->getPartnerId(),
                    'vendor_id' => $this->config->getVendorId(),
                    'instructions' => $this->config->getInstructions(),
                ],
            ],
        ];
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;

class DefaultPaymentMethodConfigProvider implements ConfigProviderInterface
{
    private CookieManagerInterface $cookieManager;

    public function __construct(CookieManagerInterface $cookieManager)
    {
        $this->cookieManager = $cookieManager;
    }

    public function getConfig(): array
    {
        $defaultPaymentMethod = $this->cookieManager->getCookie('default_payment_method');

        return ['default_payment_method' => $defaultPaymentMethod];
    }
}

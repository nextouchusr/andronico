<?php
declare(strict_types=1);

namespace Nextouch\Wins\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class WinsConfig extends AbstractHelper
{
    private const XML_PATH_GENERAL_BASE_URL = 'wins/general/base_url';
    private const XML_PATH_AUTHENTICATION_USERNAME = 'wins/authentication/username';
    private const XML_PATH_AUTHENTICATION_PASSWORD = 'wins/authentication/password';
    private const XML_PATH_MAGENTO_USERNAME = 'wins/magento/username';
    private const XML_PATH_MAGENTO_PASSWORD = 'wins/magento/password';

    public function getBaseUrl(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_GENERAL_BASE_URL);
    }

    public function getAuthUsername(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_AUTHENTICATION_USERNAME);
    }

    public function getAuthPassword(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_AUTHENTICATION_PASSWORD);
    }

    public function getMagentoUsername(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_MAGENTO_USERNAME);
    }

    public function getMagentoPassword(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_MAGENTO_PASSWORD);
    }
}

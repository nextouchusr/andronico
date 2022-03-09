<?php
declare(strict_types=1);

namespace Nextouch\Wins\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class WinsConfig extends AbstractHelper
{
    private const XML_PATH_GENERAL_BASE_URL = 'wins/general/base_url';
    private const XML_PATH_GENERAL_USERNAME = 'wins/general/username';
    private const XML_PATH_GENERAL_PASSWORD = 'wins/general/password';

    public function getBaseUrl(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_GENERAL_BASE_URL);
    }

    public function getUsername(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_GENERAL_USERNAME);
    }

    public function getPassword(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_GENERAL_PASSWORD);
    }
}

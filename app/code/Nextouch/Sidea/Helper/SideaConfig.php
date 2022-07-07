<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class SideaConfig extends AbstractHelper
{
    private const XML_PATH_AUTHENTICATION_BASE_URL = 'sidea/authentication/base_url';
    private const XML_PATH_AUTHENTICATION_GRANT_TYPE = 'sidea/authentication/grant_type';
    private const XML_PATH_AUTHENTICATION_CLIENT_ID = 'sidea/authentication/client_id';
    private const XML_PATH_AUTHENTICATION_CLIENT_SECRET = 'sidea/authentication/client_secret';
    private const XML_PATH_AUTHENTICATION_ACCOUNT_ID = 'sidea/authentication/account_id';

    private const XML_PATH_EVENTS_BASE_URL = 'sidea/events/base_url';
    private const XML_PATH_EVENTS_ABANDONED_CART_EVENT_KEY = 'sidea/events/abandoned_cart_event_key';

    public function getAuthBaseUrl(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_AUTHENTICATION_BASE_URL);
    }

    public function getAuthGrantType(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_AUTHENTICATION_GRANT_TYPE);
    }

    public function getAuthClientId(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_AUTHENTICATION_CLIENT_ID);
    }

    public function getAuthClientSecret(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_AUTHENTICATION_CLIENT_SECRET);
    }

    public function getAuthAccountId(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_AUTHENTICATION_ACCOUNT_ID);
    }

    public function getEventsBaseUrl(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_EVENTS_BASE_URL);
    }

    public function getAbandonedCartEventKey(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_EVENTS_ABANDONED_CART_EVENT_KEY);
    }
}

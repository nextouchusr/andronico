<?php
declare(strict_types=1);

namespace Nextouch\Gls\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class GlsConfig extends AbstractHelper
{
    private const XML_PATH_GLS_ACTIVE = 'carriers/gls/active';
    private const XML_PATH_GLS_TITLE = 'carriers/gls/title';
    private const XML_PATH_GLS_NAME = 'carriers/gls/name';
    private const XML_PATH_GLS_TRACKING_BASE_URL = 'carriers/gls/tracking_base_url';
    private const XML_PATH_GLS_SALLOWSPECIFIC = 'carriers/gls/sallowspecific';
    private const XML_PATH_GLS_SPECIFICCOUNTRY = 'carriers/gls/specificcountry';
    private const XML_PATH_GLS_SHOWMETHOD = 'carriers/gls/showmethod';
    private const XML_PATH_GLS_SORT_ORDER = 'carriers/gls/sort_order';

    public function isActive(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GLS_ACTIVE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getTitle(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_GLS_TITLE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getName(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_GLS_NAME,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getTrackingBaseUrl(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_GLS_TRACKING_BASE_URL,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getTrackingLink(string $trackNumber, string $scopeCode = null): string
    {
        $trackingBaseUrl = $this->getTrackingBaseUrl($scopeCode);
        $trackNumber = str_replace(' ', '/', $trackNumber);

        return sprintf('%s/%s', $trackingBaseUrl, $trackNumber);
    }

    public function allowsSpecificCountries(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GLS_SALLOWSPECIFIC,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getSpecificCountries(string $scopeCode = null): array
    {
        $countries = (string) $this->scopeConfig->getValue(
            self::XML_PATH_GLS_SPECIFICCOUNTRY,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );

        return explode(',', $countries);
    }

    public function showMethod(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GLS_SHOWMETHOD,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getSortOrder(string $scopeCode = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_GLS_SORT_ORDER,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }
}

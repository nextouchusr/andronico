<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class FastEstConfig extends AbstractHelper
{
    private const XML_PATH_FAST_EST_ACTIVE = 'carriers/fast_est/active';
    private const XML_PATH_FAST_EST_TITLE = 'carriers/fast_est/title';
    private const XML_PATH_FAST_EST_NAME = 'carriers/fast_est/name';
    private const XML_PATH_FAST_EST_WSDL = 'carriers/fast_est/wsdl';
    private const XML_PATH_FAST_EST_USERNAME = 'carriers/fast_est/username';
    private const XML_PATH_FAST_EST_PASSWORD = 'carriers/fast_est/password';
    private const XML_PATH_FAST_EST_SALLOWSPECIFIC = 'carriers/fast_est/sallowspecific';
    private const XML_PATH_FAST_EST_SPECIFICCOUNTRY = 'carriers/fast_est/specificcountry';
    private const XML_PATH_FAST_EST_SHOWMETHOD = 'carriers/fast_est/showmethod';
    private const XML_PATH_FAST_EST_SORT_ORDER = 'carriers/fast_est/sort_order';

    public function isActive(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_FAST_EST_ACTIVE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getTitle(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FAST_EST_TITLE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getName(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FAST_EST_NAME,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getWsdl(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FAST_EST_WSDL,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getUsername(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FAST_EST_USERNAME,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getPassword(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FAST_EST_PASSWORD,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function allowsSpecificCountries(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_FAST_EST_SALLOWSPECIFIC,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getSpecificCountries(string $scopeCode = null): array
    {
        $countries = (string) $this->scopeConfig->getValue(
            self::XML_PATH_FAST_EST_SPECIFICCOUNTRY,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );

        return explode(',', $countries);
    }

    public function showMethod(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_FAST_EST_SHOWMETHOD,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getSortOrder(string $scopeCode = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_FAST_EST_SORT_ORDER,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }
}

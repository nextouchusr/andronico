<?php
declare(strict_types=1);

namespace Nextouch\Dhl\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class DhlConfig extends AbstractHelper
{
    private const XML_PATH_DHL_ACTIVE = 'carriers/dhl/active';
    private const XML_PATH_DHL_TITLE = 'carriers/dhl/title';
    private const XML_PATH_DHL_NAME = 'carriers/dhl/name';
    private const XML_PATH_DHL_SALLOWSPECIFIC = 'carriers/dhl/sallowspecific';
    private const XML_PATH_DHL_SPECIFICCOUNTRY = 'carriers/dhl/specificcountry';
    private const XML_PATH_DHL_SHOWMETHOD = 'carriers/dhl/showmethod';
    private const XML_PATH_DHL_SORT_ORDER = 'carriers/dhl/sort_order';

    public function isActive(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DHL_ACTIVE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getTitle(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_DHL_TITLE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getName(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_DHL_NAME,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function allowsSpecificCountries(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DHL_SALLOWSPECIFIC,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getSpecificCountries(string $scopeCode = null): array
    {
        $countries = (string) $this->scopeConfig->getValue(
            self::XML_PATH_DHL_SPECIFICCOUNTRY,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );

        return explode(',', $countries);
    }

    public function showMethod(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DHL_SHOWMETHOD,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getSortOrder(string $scopeCode = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_DHL_SORT_ORDER,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }
}

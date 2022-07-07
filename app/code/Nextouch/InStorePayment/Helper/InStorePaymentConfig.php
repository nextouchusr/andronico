<?php
declare(strict_types=1);

namespace Nextouch\InStorePayment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class InStorePaymentConfig extends AbstractHelper
{
    private const XML_PATH_INSTORE_PAYMENT_ACTIVE = 'payment/instore_payment/active';
    private const XML_PATH_INSTORE_PAYMENT_TITLE = 'payment/instore_payment/title';
    private const XML_PATH_INSTORE_PAYMENT_ORDER_STATUS = 'payment/instore_payment/order_status';
    private const XML_PATH_INSTORE_PAYMENT_ALLOWSPECIFIC = 'payment/instore_payment/allowspecific';
    private const XML_PATH_INSTORE_PAYMENT_SPECIFICCOUNTRY = 'payment/instore_payment/specificcountry';
    private const XML_PATH_INSTORE_PAYMENT_INSTRUCTIONS = 'payment/instore_payment/instructions';
    private const XML_PATH_INSTORE_PAYMENT_SORT_ORDER = 'payment/instore_payment/sort_order';

    public function isActive(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_INSTORE_PAYMENT_ACTIVE,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }

    public function getTitle(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_INSTORE_PAYMENT_TITLE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getOrderStatus(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_INSTORE_PAYMENT_ORDER_STATUS,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }

    public function allowsSpecificCountries(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_INSTORE_PAYMENT_ALLOWSPECIFIC,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }

    public function getSpecificCountries(string $scopeCode = null): array
    {
        $countries = (string) $this->scopeConfig->getValue(
            self::XML_PATH_INSTORE_PAYMENT_SPECIFICCOUNTRY,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        return explode(',', $countries);
    }

    public function getInstructions(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_INSTORE_PAYMENT_INSTRUCTIONS,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getSortOrder(string $scopeCode = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_INSTORE_PAYMENT_SORT_ORDER,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }
}

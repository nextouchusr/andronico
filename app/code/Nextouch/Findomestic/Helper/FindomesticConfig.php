<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Store\Model\ScopeInterface;

class FindomesticConfig extends AbstractHelper
{
    private const CERT_FILEPATH = '/certificates/NEXTOUCHFindomesticMutualAuth.crt.pem';
    private const SSL_KEY_FILEPATH = '/certificates/NEXTOUCHFindomesticMutualAuth.key.pem';

    private const XML_PATH_FINDOMESTIC_ACTIVE = 'payment/findomestic_paymentservice/active';
    private const XML_PATH_FINDOMESTIC_BASE_URL = 'payment/findomestic_paymentservice/base_url';
    private const XML_PATH_FINDOMESTIC_PARTNER_ID = 'payment/findomestic_paymentservice/partner_id';
    private const XML_PATH_FINDOMESTIC_VENDOR_ID = 'payment/findomestic_paymentservice/vendor_id';
    private const XML_PATH_FINDOMESTIC_TITLE = 'payment/findomestic_paymentservice/title';
    private const XML_PATH_FINDOMESTIC_ORDER_STATUS = 'payment/findomestic_paymentservice/order_status';
    private const XML_PATH_FINDOMESTIC_ALLOWSPECIFIC = 'payment/findomestic_paymentservice/allowspecific';
    private const XML_PATH_FINDOMESTIC_SPECIFICCOUNTRY = 'payment/findomestic_paymentservice/specificcountry';
    private const XML_PATH_FINDOMESTIC_INSTRUCTIONS = 'payment/findomestic_paymentservice/instructions';
    private const XML_PATH_FINDOMESTIC_SORT_ORDER = 'payment/findomestic_paymentservice/sort_order';

    private DirectoryList $directoryList;

    public function __construct(Context $context, DirectoryList $directoryList)
    {
        parent::__construct($context);
        $this->directoryList = $directoryList;
    }

    public function getCertFilePath(): string
    {
        return $this->directoryList->getRoot() . self::CERT_FILEPATH;
    }

    public function getSslKeyFilePath(): string
    {
        return $this->directoryList->getRoot() . self::SSL_KEY_FILEPATH;
    }

    public function isActive(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_FINDOMESTIC_ACTIVE,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }

    public function getBasePath(string $scopeCode = null): string
    {
        return sprintf(
            '%s/partners/%s/merchants/%s',
            $this->getBaseUrl($scopeCode),
            $this->getPartnerId($scopeCode),
            $this->getVendorId($scopeCode)
        );
    }

    public function getBaseUrl(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FINDOMESTIC_BASE_URL,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }

    public function getPartnerId(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FINDOMESTIC_PARTNER_ID,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }

    public function getVendorId(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FINDOMESTIC_VENDOR_ID,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }

    public function getTitle(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FINDOMESTIC_TITLE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getOrderStatus(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FINDOMESTIC_ORDER_STATUS,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }

    public function allowsSpecificCountries(string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_FINDOMESTIC_ALLOWSPECIFIC,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }

    public function getSpecificCountries(string $scopeCode = null): array
    {
        $countries = (string) $this->scopeConfig->getValue(
            self::XML_PATH_FINDOMESTIC_SPECIFICCOUNTRY,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );

        return explode(',', $countries);
    }

    public function getInstructions(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_FINDOMESTIC_INSTRUCTIONS,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getSortOrder(string $scopeCode = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_FINDOMESTIC_SORT_ORDER,
            ScopeInterface::SCOPE_WEBSITE,
            $scopeCode
        );
    }
}

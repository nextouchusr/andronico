<?php
declare(strict_types=1);

namespace Nextouch\Theme\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class HomepageConfig extends AbstractHelper
{
    use ImageConfigHelper;

    private const XML_PATH_DECISION_TREE = 'theme/homepage/decision_tree';
    private const XML_PATH_MAIN_BANNER_IMAGE = 'theme/homepage/main_banner_image';
    private const XML_PATH_MAIN_BANNER_URL = 'theme/homepage/main_banner_url';
    private const XML_PATH_PROMOTION_IMAGE_1 = 'theme/homepage/promotion_image_1';
    private const XML_PATH_PROMOTION_URL_1 = 'theme/homepage/promotion_url_1';
    private const XML_PATH_PROMOTION_IMAGE_2 = 'theme/homepage/promotion_image_2';
    private const XML_PATH_PROMOTION_URL_2 = 'theme/homepage/promotion_url_2';

    protected function getUploadDir(): string
    {
        return 'nextouch/homepage/';
    }

    public function getDecisionTreeUrl(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_DECISION_TREE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getMainBannerImage(string $scopeCode = null): string
    {
        return $this->getImagePathUrl(
            self::XML_PATH_MAIN_BANNER_IMAGE,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getMainBannerUrl(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_MAIN_BANNER_URL,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getPromotionImage1(string $scopeCode = null): string
    {
        return $this->getImagePathUrl(
            self::XML_PATH_PROMOTION_IMAGE_1,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getPromotionUrl1(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_PROMOTION_URL_1,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getPromotionImage2(string $scopeCode = null): string
    {
        return $this->getImagePathUrl(
            self::XML_PATH_PROMOTION_IMAGE_2,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }

    public function getPromotionUrl2(string $scopeCode = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_PROMOTION_URL_2,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }
}

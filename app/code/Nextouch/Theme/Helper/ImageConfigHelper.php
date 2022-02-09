<?php
declare(strict_types=1);

namespace Nextouch\Theme\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

trait ImageConfigHelper
{
    protected StoreManagerInterface $storeManager;
    protected $scopeConfig;

    public function __construct(StoreManagerInterface $storeManager, ScopeConfigInterface $scopeConfig)
    {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    protected function getImagePathUrl(string $path, string $scopeType = 'default', string $scopeCode = null): string
    {
        try {
            return $this->getUploadDirUrl($scopeCode) . $this->scopeConfig->getValue($path, $scopeType, $scopeCode);
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }

    /**
     * @throws NoSuchEntityException
     */
    private function getUploadDirUrl(string $scopeCode = null): string
    {
        /** @var Store $store */
        $store = $this->storeManager->getStore($scopeCode);

        return $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $this->getUploadDir();
    }

    abstract protected function getUploadDir(): string;
}

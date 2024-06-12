<?php

namespace Nextouch\GestLogis\Block\Catalog\Product;

use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Url\EncoderInterface as UrlEncoderInterface;
use Magento\Framework\Json\EncoderInterface as JsonEncoderInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Catalog\Helper\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Customer\Model\Session;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Nextouch\GestLogis\Helper\Data as DataHelper;

class GestlogisPostcode extends View
{
    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * __construct
     *
     * @param Context $context
     * @param UrlEncoderInterface $urlEncoder
     * @param JsonEncoderInterface $jsonEncoder
     * @param StringUtils $string
     * @param Product $productHelper
     * @param ConfigInterface $productTypeConfig
     * @param FormatInterface $localeFormat
     * @param Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param PriceCurrencyInterface $priceCurrency
     * @param DataHelper $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlEncoderInterface $urlEncoder,
        JsonEncoderInterface $jsonEncoder,
        StringUtils $string,
        Product $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        DataHelper $dataHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data,
        );

        $this->_dataHelper = $dataHelper;
    }

    /**
     * GetProductId
     *
     * @return int
     */
    public function getProductId()
    {
        try {
            $product = $this->getProduct();
            if ($product && $product->getId()) {
                return $product->getId();
            }
        } catch (\Exception $e) {
            // Do Nothing.
        }

        return 0;
    }

    /**
     * GetAttributeSetId
     *
     * @return int
     */
    public function getAttributeSetId()
    {
        $product = $this->getProduct();
        if ($product && $product->getAttributeSetId()) {
            return $product->getAttributeSetId();
        }

        return 0;
    }

    /**
     * DataHelperObj
     *
     * @return DataHelper
     */
    public function dataHelperObj()
    {
        return $this->_dataHelper;
    }

    /**
     * IsServicesAvailable
     *
     * @return boolean
     */
    public function isServicesAvailable()
    {
        return $this->dataHelperObj()->isServicesAvailable($this->getAttributeSetId());
    }

    /**
     * IsProductAllowedForGestlogis
     *
     * @return boolean
     */
    public function isProductAllowedForGestlogis()
    {
        $currentProduct = $this->getProduct();
        return $this->dataHelperObj()->isProductAllowedForGestlogis($currentProduct);
    }

    /**
     * IsPostcodeAllowedForGestlogis
     *
     * @return int|null
     */
    public function isPostcodeAllowedForGestlogis()
    {
        try {
            $currentProduct = $this->getProduct();
            $postcode = $this->dataHelperObj()->getPostcodeFromSession();
            return $this->dataHelperObj()->isPostcodeAllowedForGestlogis($currentProduct, $postcode);
        } catch (\Exception $e) {
            // Do Nothing.
        }

        return null;
    }

    /**
     * GetServicesList
     *
     * @return array
     */
    public function getServicesList()
    {
        try {
            $currentProduct = $this->getProduct();
            return $this->dataHelperObj()->getServicesList($currentProduct);
        } catch (\Exception $e) {
            // Do Nothing.
        }

        return [];
    }
}

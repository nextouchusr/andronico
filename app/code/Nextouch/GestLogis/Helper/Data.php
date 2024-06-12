<?php

namespace Nextouch\GestLogis\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Nextouch\GestLogis\Model\ResourceModel\ShippingPostcode\CollectionFactory as ShippingPostcodeCollectionFactory;
use Nextouch\GestLogis\Model\ResourceModel\ShippingServices\CollectionFactory as ShippingServicesCollectionFactory;
use Nextouch\GestLogis\Model\ResourceModel\QuoteShippingService\CollectionFactory as QuoteShippingServiceCollectionFactory;
use Nextouch\GestLogis\Model\ResourceModel\OrderShippingService\CollectionFactory as OrderShippingServiceCollectionFactory;
use Nextouch\GestLogis\Model\ResourceModel\Attributes\CollectionFactory as AttributesCollectionFactory;
use Nextouch\GestLogis\Model\ResourceModel\PostcodeServiceAttributes\CollectionFactory as PostcodeServiceAttributesCollectionFactory;
use Nextouch\GestLogis\Model\QuoteShippingServiceFactory;
use Nextouch\GestLogis\Model\OrderShippingServiceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;

class Data extends AbstractHelper
{
    public const GESTLOGIS_SESSION_POSTCODE = "gestlogis_postcode";
    public const INVALID_FORM_KEY = 'Invalid Form Key!';
    public const GESTLOGIS_GENERAL_ACTIVE = 'carriers/gestlogis/active';
    public const GESTLOGIS_GENERAL_TOOLTIP = 'carriers/gestlogis/tooltip';
    public const GESTLOGIS_DEFAULT_SHIP_NAME = 'Gestlogis Default';
    public const GESTLOGIS_DEFAULT_SHIP_CODE = 'default';

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ShippingPostcodeCollectionFactory
     */
    protected $_shippingPostcodeCollectionFactory;

    /**
     * @var ShippingServicesCollectionFactory
     */
    protected $_shippingServicesCollectionFactory;

    /**
     * @var QuoteShippingServiceCollectionFactory
     */
    protected $_quoteShippingServiceCollectionFactory;

    /**
     * @var OrderShippingServiceCollectionFactory
     */
    protected $_orderShippingServiceCollectionFactory;

    /**
     * @var AttributesCollectionFactory
     */
    protected $_attributesCollectionFactory;

    /**
     * @var PostcodeServiceAttributesCollectionFactory
     */
    protected $_postcodeServiceAttributesCollectionFactory;

    /**
     * @var QuoteShippingServiceFactory
     */
    protected $_quoteShippingService;

    /**
     * @var OrderShippingServiceFactory
     */
    protected $_orderShippingService;

    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;

    /**
     * @var Validator
     */
    protected $_validator;

    /**
     * @var ResourceConnection
     */
    protected $_resource;

    /**
     * @var PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ShippingPostcodeCollectionFactory $shippingPostcodeCollectionFactory,
        ShippingServicesCollectionFactory $shippingServicesCollectionFactory,
        QuoteShippingServiceCollectionFactory $quoteShippingServiceCollectionFactory,
        OrderShippingServiceCollectionFactory $orderShippingServiceCollectionFactory,
        AttributesCollectionFactory $attributesCollectionFactory,
        PostcodeServiceAttributesCollectionFactory $postcodeServiceAttributesCollectionFactory,
        QuoteShippingServiceFactory $quoteShippingService,
        OrderShippingServiceFactory $orderShippingService,
        CheckoutSession $checkoutSession,
        Validator $validator,
        ResourceConnection $resource,
        PriceCurrencyInterface $priceCurrency,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_storeManager = $storeManager;
        $this->_shippingPostcodeCollectionFactory = $shippingPostcodeCollectionFactory;
        $this->_shippingServicesCollectionFactory = $shippingServicesCollectionFactory;
        $this->_quoteShippingServiceCollectionFactory = $quoteShippingServiceCollectionFactory;
        $this->_orderShippingServiceCollectionFactory = $orderShippingServiceCollectionFactory;
        $this->_attributesCollectionFactory = $attributesCollectionFactory;
        $this->_postcodeServiceAttributesCollectionFactory = $postcodeServiceAttributesCollectionFactory;
        $this->_quoteShippingService = $quoteShippingService;
        $this->_orderShippingService = $orderShippingService;
        $this->_checkoutSession = $checkoutSession;
        $this->_validator = $validator;
        $this->_resource = $resource;
        $this->_priceCurrency = $priceCurrency;
        $this->_productRepository = $productRepository;
    }

    /**
     * GetResourceConnectionObj
     *
     * @return ResourceConnection
     */
    public function getResourceConnectionObj()
    {
        return $this->_resource;
    }

    /**
     * GetProductById
     *
     * @return object|null
     */
    public function getProductById($productId, $storeId = null)
    {
        try {
            $product = $this->_productRepository->getById($productId, false, $storeId);

            return $product;
        } catch (\Exception $e) {
            // Do Nothing.
        }

        return null;
    }

    /**
     * GetModuleConfig
     *
     * @param string $path
     * @param int|null $storeId
     * @return mixed
     */
    public function getModuleConfig($path, $storeId = null)
    {
        return $this->_scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * IsShippingActive
     *
     * @param int|null $storeId
     * @return boolean
     */
    public function isShippingActive($storeId = null)
    {
        return (bool) $this->getModuleConfig(self::GESTLOGIS_GENERAL_ACTIVE, $storeId);
    }

    /**
     * GetShippingTooltip
     *
     * @param int|null $storeId
     * @return string
     */
    public function getShippingTooltip($storeId = null)
    {
        return $this->getModuleConfig(self::GESTLOGIS_GENERAL_TOOLTIP, $storeId);
    }

    /**
     * GetStore
     *
     * @return StoreManagerInterface
     */
    public function getStore()
    {
        return $this->_storeManager->getStore();
    }

    /**
     * GetCurrencySymbol
     *
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->getStore()->getBaseCurrency()->getCurrencySymbol();
    }

    /**
     * GetShippingServicesCollectionObj
     *
     * @return ShippingServicesCollectionFactory
     */
    public function getShippingServicesCollectionObj()
    {
        return $this->_shippingServicesCollectionFactory->create();
    }

    /**
     * GetShippingPriceCollectionObj
     *
     * @return ShippingPriceCollectionFactory
     */
    public function getShippingPriceCollectionObj()
    {
        return $this->_shippingPostcodeCollectionFactory->create();
    }

    /**
     * GetAttributesCollectionObj
     *
     * @return AttributesCollectionFactory
     */
    public function getAttributesCollectionObj()
    {
        return $this->_attributesCollectionFactory->create();
    }

    /**
     * GetPostcodeServiceAttributesCollectionObj
     *
     * @return PostcodeServiceAttributesCollectionFactory
     */
    public function getPostcodeServiceAttributesCollectionObj()
    {
        return $this->_postcodeServiceAttributesCollectionFactory->create();
    }

    /**
     * IsServicesAvailable
     *
     * @param int $attributeSetId
     * @return boolean
     */
    public function isServicesAvailable($attributeSetId)
    {
        $servicesCollection = $this->getShippingServicesCollectionObj();
        return $servicesCollection->isServicesAvailable($attributeSetId);
    }

    /**
     * IsPostcodeAvailable
     *
     * @param int $postcode
     * @return boolean
     */
    public function isPostcodeAvailable($postcode)
    {
        $collection = $this->getShippingPriceCollectionObj();
        return $collection->isPostcodeAvailable($postcode);
    }

    /**
     * GetPostcodePrice
     *
     * @param object $product
     * @param int $postcode
     * @return int|null
     */
    public function getPostcodePrice($product, $postcode)
    {
        $postcodePrice = null;
        $servicesCollection = $this->getShippingPriceCollectionObj();
        $postcodeCollection = $servicesCollection->getPostcodeAttributesCollection($product->getAttributeSetId(), $postcode, false, true);

        $attributeMatches = $this->isAttributesMatches($postcodeCollection, $product, true);

        if (isset($attributeMatches['attributeFound']) && $attributeMatches['attributeFound']) {
            $postcodeResponse = isset($attributeMatches['matchedResponse'][0]) ? $attributeMatches['matchedResponse'][0] : [];
            if (isset($postcodeResponse['postcode_price'])) {
                $postcodePrice = (float) $postcodeResponse['postcode_price'];
            } else if (isset($postcodeResponse[0]['postcode_price'])) {
                $postcodePrice = (float) $postcodeResponse[0]['postcode_price'];
            }
        }

        return $postcodePrice;
    }

    /**
     * GetShippingPrice
     *
     * @param int $postcode
     * @param boolean $withCurrencySymbol
     * @return mixed
     */
    public function getShippingPrice($postcode, $withCurrencySymbol = true)
    {
        $servicesCollection = $this->getShippingPriceCollectionObj();
        $shippingPrice = $servicesCollection->getShippingPrice($postcode);

        if ($shippingPrice && $shippingPrice->getPrice()) {
            $price = $shippingPrice->getPostcodePrice() ? $shippingPrice->getPostcodePrice() : $shippingPrice->getPrice();

            if ($withCurrencySymbol) {
                return $this->getFormatedPrice($price);
            }

            return (float) $price;
        }

        return $shippingPrice->getSize() ? $shippingPrice : null;
    }

    /**
     * IsAttributesMatches
     *
     * @param object $collection
     * @param object $product
     * @param boolean $matchedResponse
     * @return boolean|array
     */
    public function isAttributesMatches($collection, $product, $matchedResponse = false)
    {
        $attributeFlagContainer = [];
        $attributeFound = false;
        $matchedDataArray = [];
        if ($collection->getSize() && $collection->getData()) {
            foreach ($collection->getData() as $postcodeServiceAttributesData) {
                if ($postcodeServiceAttributesData['attribute_set_id'] == null) {
                    $attributeFound = false;
                    break;
                }

                if ($postcodeServiceAttributesData['attribute_id'] == null) {
                    $attributeFound = true;
                    $matchedDataArray = $collection->getData();
                    break;
                }

                if (!isset($postcodeServiceAttributesData['attribute_code'])) {
                    $attributeFound = true;
                    $matchedDataArray = $collection->getData();
                    break;
                }

                // $productAttribVal = $product->getData($postcodeServiceAttributesData['attribute_code']);
                $productAttribVal = $product->getResource()->getAttribute($postcodeServiceAttributesData['attribute_code'])->getFrontend()->getValue($product);

                if($product->getSku() == "AT-2220010292") {
                    file_put_contents(BP . '/var/log/gestlogic-new.log', print_r([
                        'productAttribVal' => $productAttribVal,
                        'postcodeServiceAttributesData' => $postcodeServiceAttributesData
                    ], true)."\n", FILE_APPEND);			
                }

                
                if (is_int($productAttribVal)) {
                    $productAttribVal = number_format((float) $productAttribVal, 2, '.', '');
                }

                if ($productAttribVal && $productAttribVal == $postcodeServiceAttributesData['attribute_value']) {
                    $attributeFlagContainer[$postcodeServiceAttributesData['attribute_code']] = true;
                    $matchedDataArray[] = $postcodeServiceAttributesData;
                } elseif ($productAttribVal && strcasecmp($productAttribVal, $postcodeServiceAttributesData['attribute_value']) == 0) {
                    $attributeFlagContainer[$postcodeServiceAttributesData['attribute_code']] = true;
                    $matchedDataArray[] = $postcodeServiceAttributesData;
                } else {
                    if (isset($attributeFlagContainer[$postcodeServiceAttributesData['attribute_code']]) && $attributeFlagContainer[$postcodeServiceAttributesData['attribute_code']]) {
                        $attributeFlagContainer[$postcodeServiceAttributesData['attribute_code']] = true;
                    } else {
                        $attributeFlagContainer[$postcodeServiceAttributesData['attribute_code']] = false;
                    }
                }
            }
        }

        if (count($attributeFlagContainer)) {
            $attributeFound = false;
            foreach ($attributeFlagContainer as $attributeCode => $attributeStatus) {
                if ($attributeStatus) {
                    $attributeFound = true;
                } else {
                    $attributeFound = false;
                }
            }
        }

        if ($matchedResponse) {
            return [
                'attributeFound' => $attributeFound,
                'matchedResponse' => $matchedDataArray,
            ];
        }

        return $attributeFound;
    }

    /**
     * GetCheckoutSession
     *
     * @return CheckoutSession
     */
    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    /**
     * GetSessionValue
     *
     * @param string $name
     * @return mixed
     */
    public function getSessionValue($name)
    {
        $checkoutSession = $this->getCheckoutSession();
        if ($checkoutSession->hasData($name)) {
            return $checkoutSession->getData($name);
        }

        return null;
    }

    /**
     * SetSessionValue
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function setSessionValue($name, $value)
    {
        $checkoutSession = $this->getCheckoutSession();
        $checkoutSession->setData($name, $value);

        return $this;
    }

    /**
     * UnsetSessionValue
     *
     * @param string $name
     * @return mixed
     */
    public function unsetSessionValue($name)
    {
        $checkoutSession = $this->getCheckoutSession();
        $checkoutSession->unsetData($name);

        return $this;
    }

    /**
     * ValidateFormKey
     *
     * @return Validator
     */
    public function validateFormKey()
    {
        return $this->_validator;
    }

    /**
     * InvalidFormKeyResponse
     *
     * @param string $msg
     * @return array
     */
    public function invalidFormKeyResponse(string $msg = self::INVALID_FORM_KEY)
    {
        $response = [
            'success' => false,
            'message' => __($msg)
        ];

        return $response;
    }

    /**
     * GetQuoteShippingServiceCollectionObj
     *
     * @return QuoteShippingServiceCollectionFactory
     */
    public function getQuoteShippingServiceCollectionObj()
    {
        return $this->_quoteShippingServiceCollectionFactory->create();
    }

    /**
     * GetOrderShippingServiceCollectionObj
     *
     * @return OrderShippingServiceCollectionFactory
     */
    public function getOrderShippingServiceCollectionObj()
    {
        return $this->_orderShippingServiceCollectionFactory->create();
    }

    public function saveQuoteShippingServiceAll($itemId, $services, $product)
    {
        $_services = $this->getServicesList($product);

        foreach ($services as $service) {

            $_service = null;

            foreach ($_services as $s) {
                if ($service->getServiceCode() == $s['service_code']) {
                    $_service = $s;
                    break;
                }
            }
          
            if ($_service) {
                $shipQuoteCollection = $this->getQuoteShippingServiceCollectionObj();

                if (!$shipQuoteCollection->isSameServiceExistsWithItem($itemId, $service->getServiceCode())) {
                    $quoteShippingModel = $this->_quoteShippingService->create();
                    $quoteShippingModel->setItemId($itemId);
                    $quoteShippingModel->setServiceCode($service->getServiceCode());
                    $quoteShippingModel->setServiceName($service->getServiceName());
                    $quoteShippingModel->setPrice($_service['service_price']);
                    $quoteShippingModel->save();
                }
            } else if ($service->getServiceCode() == self::GESTLOGIS_DEFAULT_SHIP_CODE) {
                $postcode = $this->getPostcodeFromSession();
                $defaultShippingPrice = $this->getPostcodePrice($product, $postcode);

                if (!$shipQuoteCollection->isSameServiceExistsWithItem($itemId, $service->getServiceCode())) {
                    $quoteShippingModel = $this->_quoteShippingService->create();
                    $quoteShippingModel->setItemId($itemId);
                    $quoteShippingModel->setServiceCode($service->getServiceCode());
                    $quoteShippingModel->setServiceName($service->getServiceName());
                    $quoteShippingModel->setPrice($defaultShippingPrice);
                    $quoteShippingModel->save();
                }
            }
        }
    }

    /**
     * SaveQuoteShippingService
     *
     * @param int $itemId
     * @param int $serviceId
     * @param object $product
     * @return void
     */
    public function saveQuoteShippingService($itemId, $serviceId, $product)
    {
        $postcode = $this->getPostcodeFromSession();
        $services = $this->getServicesList($product);

        foreach ($services as $service) {
            if ($service['entity_id'] == $serviceId) {
                $shipQuoteCollection = $this->getQuoteShippingServiceCollectionObj();
                if (!$shipQuoteCollection->isSameServiceExistsWithItem($itemId, $service['service_code'])) {
                    $quoteShippingModel = $this->_quoteShippingService->create();
                    $quoteShippingModel->setItemId($itemId);
                    $quoteShippingModel->setServiceCode($service['service_code']);
                    $quoteShippingModel->setServiceName($service['service_name']);
                    $quoteShippingModel->setPrice($service['service_price']);
                    $quoteShippingModel->save();
                }
            }
        }

        /*$serviceCollection = $this->getShippingServicesCollectionObj();
        $service = $serviceCollection->_getServiceById($serviceId, $postcode, $product->getAttributeSetId());
        if ($service && $service->getId()) {
            $shipQuoteCollection = $this->getQuoteShippingServiceCollectionObj();
            if (!$shipQuoteCollection->isSameServiceExistsWithItem($itemId, $service->getServiceCode())) {
                $quoteShippingModel = $this->_quoteShippingService->create();
                $quoteShippingModel->setItemId($itemId);
                $quoteShippingModel->setServiceCode($service->getServiceCode());
                $quoteShippingModel->setServiceName($service->getServiceName());
                $quoteShippingModel->setPrice($service->getServicePrice());
                $quoteShippingModel->save();
            }
        }*/
    }

    /**
     * AddDefaultShipping
     *
     * @param int $itemId
     * @return void
     */
    public function addDefaultShipping($itemId, $product, $postcode)
    {
        //$defaultShippingPrice = $this->getPostcodePrice($product, $postcode);
        $streetLineDeliveryPrice = ($product->getStreetLineDeliveryPrice() === null) ? 0 : $product->getStreetLineDeliveryPrice();
        $defaultShippingPrice = $streetLineDeliveryPrice;
        if ($defaultShippingPrice !== null && $itemId) {
            $shipQuoteCollection = $this->getQuoteShippingServiceCollectionObj();

            if (!$shipQuoteCollection->isSameServiceExistsWithItem($itemId, self::GESTLOGIS_DEFAULT_SHIP_CODE)) {

                $quoteShippingModel = $this->_quoteShippingService->create();
                $quoteShippingModel->setItemId($itemId);
                $quoteShippingModel->setServiceCode(self::GESTLOGIS_DEFAULT_SHIP_CODE);
                $quoteShippingModel->setServiceName(self::GESTLOGIS_DEFAULT_SHIP_NAME);
                $quoteShippingModel->setPrice($defaultShippingPrice);
                $quoteShippingModel->save();
            }
        }
    }

    /**
     * AddDefaultShipping
     *
     * @param int $itemId
     * @return void
     */
    public function addServicesDefaultShipping($itemId, $product, $postcode)
    {
        $defaultShippingPrice = $this->getPostcodePrice($product, $postcode);
        //$streetLineDeliveryPrice = ($product->getStreetLineDeliveryPrice() === null) ? 0 : $product->getStreetLineDeliveryPrice();
        //$defaultShippingPrice = $streetLineDeliveryPrice;
        if ($defaultShippingPrice !== null && $itemId) {
            $shipQuoteCollection = $this->getQuoteShippingServiceCollectionObj();

            if (!$shipQuoteCollection->isSameServiceExistsWithItem($itemId, self::GESTLOGIS_DEFAULT_SHIP_CODE)) {

                $quoteShippingModel = $this->_quoteShippingService->create();
                $quoteShippingModel->setItemId($itemId);
                $quoteShippingModel->setServiceCode(self::GESTLOGIS_DEFAULT_SHIP_CODE);
                $quoteShippingModel->setServiceName(self::GESTLOGIS_DEFAULT_SHIP_NAME);
                $quoteShippingModel->setPrice($defaultShippingPrice);
                $quoteShippingModel->save();
            }
        }
    }

    /**
     * GetServicesByItemId
     *
     * @param int $itemId
     * @param boolean $excludeJoin
     * @return object
     */
    public function getServicesByItemId($itemId, $excludeJoin = false)
    {
        $quoteServiceCollection = $this->getQuoteShippingServiceCollectionObj();
        $services = $quoteServiceCollection->getServicesByItemId($itemId, $excludeJoin);

        return $services;
    }

    /**
     * GetItemsPrice
     *
     * @param int $itemId
     * @param boolean $includedWithShippingCount
     * @return int
     */
    public function getItemsPrice($itemId, $includedWithShippingCount = true)
    {
        $quoteServiceCollection = $this->getQuoteShippingServiceCollectionObj();
        $services = $quoteServiceCollection->getServicesByItemId($itemId, true);
        $itemPrice = 0;

        if ($services && $services->getSize()) {
            foreach ($services as $service) {
                $itemPrice += (float) $service->getPrice();
            }
        }

        return $itemPrice;
    }

    /**
     * GetDefaultShippingPrice
     *
     * @param integer $postcode
     * @param boolean $withCurrencySymbol
     * @return mixed
     */
    public function getDefaultShippingPrice($postcode = 0, $withCurrencySymbol = true)
    {
        if (!$postcode) {
            $postcode = $this->getPostcodeFromSession();
        }

        $shippingPrice = $this->getShippingPrice($postcode, $withCurrencySymbol);

        return $shippingPrice;
    }

    /**
     * GetPostcodeFromSession
     *
     * @return mixed
     */
    public function getPostcodeFromSession()
    {
        $postcode = $this->getSessionValue(self::GESTLOGIS_SESSION_POSTCODE);

        return $postcode;
    }

    /**
     * GetGestLogisShipPrice
     *
     * @return mixed
     */
    public function getGestLogisShipPrice()
    {
        $checkoutSession = $this->getCheckoutSession();
        $quote = $checkoutSession->getQuote();
        $items = $quote->getAllItems();
        $gestLogisPrice = 0;

        if (count($items)) {
            foreach ($items as $item) {
                $itemId = (int) $item->getId();
                $gestLogisPrice += ($item->getQty() * (float) $this->getItemsPrice($itemId));
            }
        }


        return $gestLogisPrice;
    }

    /**
     * SaveOrderShippingService
     *
     * @param int $itemId
     * @param int $quoteItemId
     * @return void
     */
    public function saveOrderShippingService($itemId, $quoteItemId)
    {
        $shipQuoteCollection = $this->getQuoteShippingServiceCollectionObj();
        $quoteServices = $shipQuoteCollection->getServicesByItemId($quoteItemId, true);

        if ($quoteServices && $quoteServices->getSize()) {
            foreach ($quoteServices as $quoteService) {
                $orderShippingModel = $this->_orderShippingService->create();

                $orderShippingModel->setItemId($itemId);
                $orderShippingModel->setServiceCode($quoteService->getServiceCode());
                $orderShippingModel->setServiceName($quoteService->getServiceName());
                $orderShippingModel->setPrice($quoteService->getPrice());
                $orderShippingModel->save();
            }
        }
    }

    /**
     * Function getFormatedPrice
     *
     * @param float $price
     *
     * @return string
     */
    public function getFormatedPrice($amount)
    {
        return $this->_priceCurrency->convertAndFormat($amount);
    }

    /**
     * IsProductAllowedForGestlogis
     *
     * @param object $product
     * @return boolean
     */
    public function isProductAllowedForGestlogis($product)
    {
        if ($this->isShippingActive()) {
            $postcode = $this->getPostcodeFromSession();
            if ($postcode) {
                return $this->isPostcodeAllowedForGestlogis($product, $postcode);
            }
            
            if ($product && $product->getAttributeSetId()) {
                $attributeSetId = $product->getAttributeSetId();

                $postcodeServiceAttributes = $this->getPostcodeServiceAttributesCollectionObj();
                $postcodeServiceAttributes->addFieldToFilter('attribute_set_id', ['eq' => $attributeSetId]);
               
                if (!$postcodeServiceAttributes->getSize()) {
                    return false;
                }

                $postcodeServiceAttributes->getSelect()->group('main_table.attribute_id');

                $cloneCollection = clone $postcodeServiceAttributes;
                $cloneCollection->addFieldToFilter('main_table.attribute_id', ['null' => true]);

                if ($cloneCollection->getSize() && $cloneCollection->getData()) {
                    $attributeFound = $this->isAttributesMatches($cloneCollection, $product);
                    return $attributeFound;
                }

                $postcodeServiceAttributes->getSelect()->join(
                    ['nga' => 'nextouch_gestlogis_attributes'],
                    'main_table.attribute_id = nga.entity_id',
                    [
                        "attribute_code" => "attribute_code",
                        "attribute_value" => "attribute_value"
                    ]
                );
                
                if (!$postcodeServiceAttributes->getSize()) {
                    return false;
                }

                if ($postcodeServiceAttributes->getSize() && $postcodeServiceAttributes->getData()) {
                    $attributeFound = $this->isAttributesMatches($postcodeServiceAttributes, $product);
                   
                    return $attributeFound;
                }

                return false;
            }
        }

        return false;
    }

    /**
     * IsPostcodeAllowedForGestlogis
     *
     * @param object $product
     * @param int $postcode
     * @return mixed
     */
    public function isPostcodeAllowedForGestlogis($product, $postcode)
    {
        try {
            if (!$postcode) {
                $postcode = $this->getPostcodeFromSession();
            }
            $postcodeAvailability = $this->_isPostcodeAllowedForGestlogis($product, $postcode);

            return $postcodeAvailability;
        } catch (\Exception $e) {
            // Do Nothing.
        }
    }

    /**
     * _isPostcodeAllowedForGestlogis
     *
     * @param object $product
     * @param int $postcode
     * @return boolean
     */
    public function _isPostcodeAllowedForGestlogis($product, $postcode)
    {
        if ($postcode && $product && $product->getAttributeSetId()) {
            $attributeSetId = $product->getAttributeSetId();
            $postcodeCollection = $this->getShippingPriceCollectionObj();

            $postcodeCollection->getPostcodeAttributesCollection($attributeSetId, $postcode, false, true);

            $attributeMatches = $this->isAttributesMatches($postcodeCollection, $product);

            if ($attributeMatches) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function getServicesList($product)
    {
        try {
            $currentProduct = $product;
            $postcode = $this->getPostcodeFromSession();
            if ($postcode && $currentProduct && $currentProduct->getAttributeSetId()) {

                $attributeSetId = $currentProduct->getAttributeSetId();

                $collection = $this->getShippingPriceCollectionObj();
                $collection->addFieldToFilter('main_table.postcode', ['eq' => $postcode]);
                $collection->addFieldToFilter('main_table.status', ['eq' => 1]);

                $postCodeObj = $collection->getFirstItem();
                if ($postCodeObj && $postCodeObj->getId()) {

                    $postcodeCol = $this->getShippingPriceCollectionObj();
                    $postcodeCol->getPostcodeAttributesCollection($attributeSetId, $postcode, false, true);

                    $attributeMatches = $this->isAttributesMatches($postcodeCol, $currentProduct, true);

                    $postcodeResponse = [];
                    if (isset($attributeMatches['attributeFound']) && $attributeMatches['attributeFound']) {
                        $postcodeResponse = isset($attributeMatches['matchedResponse']) ? $attributeMatches['matchedResponse'] : [];
                    }

                    $servicesList = [];
                    if (count($postcodeResponse)) {
                        foreach ($postcodeResponse as $_postcodeResponse) {
                            $shippingServiceCol = $this->getShippingServicesCollectionObj();
                            $service = $shippingServiceCol->getServiceById($_postcodeResponse['service_id']);

                            if ($service) {
                                $servicePrice = ['service_price' => $_postcodeResponse['service_price'], 'is_service_required' => $_postcodeResponse['is_service_required']];
                                $servicesList[$service->getId()] = array_merge($service->getData(), $servicePrice);
                            }
                        }
                    }

                    return $servicesList;
                }

                return [];
            }
        } catch (\Exception $e) {
            // Do Nothing.
        }

        return [];
    }
}

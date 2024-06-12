<?php

namespace Nextouch\GestLogis\Observer\Cart;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Nextouch\GestLogis\Model\Carrier\Shipping as GestLogisCarrier;

class AddProduct implements ObserverInterface
{
    /**
     * @var CookieManagerInterface
     */
    private $_cookieManager;

    /**
     * @var DataHelper
     */
    private $_dataHelper;

    /**
     * __construct
     *
     * @param DataHelper $dataHelper
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        DataHelper $dataHelper
    ) {
        $this->_cookieManager = $cookieManager;
        $this->_dataHelper = $dataHelper;
    }

    /**
     * Execute
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            $shippingActive = $this->_dataHelper->isShippingActive();
            if ($shippingActive) {
                $checkoutSession = $this->_dataHelper->getCheckoutSession();
                $quote = $checkoutSession->getQuote();
                $items = $quote->getAllItems();

                if (count($items)) {
                    $gestlogis = $observer->getRequest()->getParam('gestlogis');
                    $requestedProductId = $observer->getRequest()->getParam('item');
                    $productId = $observer->getRequest()->getParam('product');
                    $gestlogisDeliveryPption = $observer->getRequest()->getParam('gestlogis_delivery_option');



                    $itemIds = [];
                    //$addToCartItems = [];
                    file_put_contents(BP . '/var/log/params-plog.log', print_r([
                        'gestlogis' => $gestlogis,
                        'requestedProductId' => $requestedProductId,
                        'productId' => $productId,
                        'gestlogisDeliveryPption' => $gestlogisDeliveryPption
                    ], true) . "\n", FILE_APPEND);


                    foreach ($items as $item) {
                        if ($requestedProductId == $item->getProductId()) {
                            $shipQuoteCollection = $this->_dataHelper->getQuoteShippingServiceCollectionObj();
                            $shipQuoteCollection->addFieldToFilter('item_id', ['eq' => $item->getId()]);

                            if (!$shipQuoteCollection->getSize()) {
                                // $addToCartItems[] = $item;
                                $itemId = (int) $item->getId();
                                $itemIds[$itemId] = $itemId;
                            }
                        }
                    }

                    $product = $this->_dataHelper->getProductById($productId);
                    $allServices = $this->_dataHelper->getServicesList($product);
                    foreach ($allServices as $service) {
                        if ($service['is_service_required']) {
                            $serviceId = $service['entity_id'];
                            $serviceName = $service['service_name'];
                            $gestlogis[$serviceId] = $serviceName;
                        }
                    }

                    $itemId = end($itemIds);

                    if ($itemId) {
                        $postcode = $this->_dataHelper->getPostcodeFromSession();
                        if ($gestlogisDeliveryPption == 'delivery_additional_services') {
                            if ($gestlogis) {
                                foreach ($gestlogis as $serviceId => $serviceOn) {
                                    $this->_dataHelper->saveQuoteShippingService($itemId, $serviceId, $product);
                                }

                                // If services comes with postcode price with 0 then allow to save
                                if ($productId && $postcode) {
                                    $postcodeAllowed = $this->_dataHelper->isPostcodeAllowedForGestlogis($product, $postcode);
                                    if ($postcodeAllowed) {
                                        $this->_dataHelper->addServicesDefaultShipping($itemId, $product, $postcode);
                                    }
                                }
                            } else {
                                // If only postcode price comes with zero then don't allow.
                                //$defaultShippingPrice = $this->_dataHelper->getPostcodePrice($product, $postcode);
                                $streetLineDeliveryPrice = ($product->getStreetLineDeliveryPrice() === null) ? 0 : $product->getStreetLineDeliveryPrice();
                                $defaultShippingPrice = $streetLineDeliveryPrice;
                                if ($defaultShippingPrice && $productId && $postcode) {
                                    $postcodeAllowed = $this->_dataHelper->isPostcodeAllowedForGestlogis($product, $postcode);
                                    if ($postcodeAllowed) {
                                        $this->_dataHelper->addDefaultShipping($itemId, $product, $postcode);
                                    }
                                }
                            }
                        } else if ($gestlogisDeliveryPption == 'standard_delivery') {
                            // If only postcode price comes with zero then don't allow.
                            //$defaultShippingPrice = $this->_dataHelper->getPostcodePrice($product, $postcode);
                            $streetLineDeliveryPrice = ($product->getStreetLineDeliveryPrice() === null) ? 0 : $product->getStreetLineDeliveryPrice();
                            $defaultShippingPrice = $streetLineDeliveryPrice;

                            if ($defaultShippingPrice && $productId && $postcode) {
                                $postcodeAllowed = $this->_dataHelper->isPostcodeAllowedForGestlogis($product, $postcode);
                                if ($postcodeAllowed) {
                                    $this->_dataHelper->addDefaultShipping($itemId, $product, $postcode);
                                }
                            }
                        }
                    }

                    /* if (count($addToCartItems)) {
                        foreach ($addToCartItems as $addToCartItem) {
                            $itemId = (int) $addToCartItem->getId();
                            $itemIds[$itemId] = $itemId;
                            if ($gestlogis) {
                                foreach ($gestlogis as $serviceId => $serviceOn) {
                                    $this->_dataHelper->saveQuoteShippingService($itemId, $serviceId, $product);
                                }
                            }
                        }
                    } */

                    /* if (count($itemIds)) {
                        $postcode = $this->_dataHelper->getPostcodeFromSession();

                        if ($productId && $postcode) {
                            $postcodeAllowed = $this->_dataHelper->isPostcodeAllowedForGestlogis($product, $postcode);
                            if ($postcodeAllowed) {
                                foreach ($itemIds as $itemId => $_itemId) {
                                    $this->_dataHelper->addDefaultShipping($itemId, $product, $postcode);
                                }
                            }
                        }
                    } */
                }
            }
        } catch (\Exception $exception) {
            // Do Nothing.
        } catch (\Error $error) {
            // Do Nothing.
        }
    }
}

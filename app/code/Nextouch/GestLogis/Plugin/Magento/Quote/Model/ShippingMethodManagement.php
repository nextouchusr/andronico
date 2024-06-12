<?php


namespace Nextouch\GestLogis\Plugin\Magento\Quote\Model;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Quote\Model\Quote\TotalsCollector;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Quote\Model\Cart\ShippingMethodConverter;
use Magento\Quote\Api\Data\EstimateAddressInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Magento\Customer\Model\Data\Address as CustomerAddress;
use Nextouch\GestLogis\Model\Carrier\Shipping as GestLogisCarrier;

class ShippingMethodManagement
{
    /**
     * @var CartRepositoryInterface
     */
    private $_quoteRepository;

    /**
     * @var TotalsCollector
     */
    private $_totalsCollector;

    /**
     * @var CustomerSession
     */
    private $_customerSession;

    /**
     * @var AddressInterfaceFactory
     */
    private $_addressFactory;

    /**
     * @var ShippingMethodConverter
     */
    private $_converter;

    /**
     * @var DataHelper
     */
    private $_dataHelper;

    /**
     * @var DataObjectProcessor
     */
    private $_dataProcessor;

    /**
     * @param CartRepositoryInterface $quoteRepository
     * @param TotalsCollector $totalsCollector
     * @param CustomerSession $customerSession
     * @param AddressInterfaceFactory $addressFactory
     * @param ShippingMethodConverter $converter
     * @param DataHelper $dataHelper
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        TotalsCollector $totalsCollector,
        CustomerSession $customerSession,
        AddressInterfaceFactory $addressFactory,
        ShippingMethodConverter $converter,
        DataHelper $dataHelper
    ) {
        $this->_quoteRepository = $quoteRepository;
        $this->_totalsCollector = $totalsCollector;
        $this->_customerSession = $customerSession;
        $this->_addressFactory = $addressFactory;
        $this->_converter = $converter;
        $this->_dataHelper = $dataHelper;
    }

    /**
     * @inheritDoc
     */
    public function aroundEstimateByAddress(\Magento\Quote\Model\ShippingMethodManagement $subject, callable $proceed, $cartId, EstimateAddressInterface $address)
    {
        /** @var Quote $quote */
        $quote = $this->_quoteRepository->getActive($cartId);

        // no methods applicable for empty carts or carts with virtual products
        if ($quote->isVirtual() || 0 == $quote->getItemsCount()) {
            return [];
        }

        if ($this->_dataHelper->isShippingActive()) {
            return $this->getShippingMethods($quote, $address);
        }

        return $proceed($cartId, $address);
    }

    /**
     * @inheritdoc
     */
    public function aroundEstimateByExtendedAddress(\Magento\Quote\Model\ShippingMethodManagement $subject, callable $proceed, $cartId, AddressInterface $address)
    {
        /** @var Quote $quote */
        $quote = $this->_quoteRepository->getActive($cartId);

        // no methods applicable for empty carts or carts with virtual products
        if ($quote->isVirtual() || 0 == $quote->getItemsCount()) {
            return [];
        }

        if ($this->_dataHelper->isShippingActive()) {
            return $this->getShippingMethods($quote, $address);
        }

        return $proceed($cartId, $address);
    }

    /**
     * @inheritDoc
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function aroundEstimateByAddressId(\Magento\Quote\Model\ShippingMethodManagement $subject, callable $proceed, $cartId, $addressId)
    {
        /** @var Quote $quote */
        $quote = $this->_quoteRepository->getActive($cartId);

        // no methods applicable for empty carts or carts with virtual products
        if ($quote->isVirtual() || 0 == $quote->getItemsCount()) {
            return [];
        }
        $address = $this->getAddress($addressId, $quote);

        if ($this->_dataHelper->isShippingActive()) {
            return $this->getShippingMethods($quote, $address);
        }

        return $this->getShippingMethods($quote, $address);
    }

    /**
     * Get estimated rates
     *
     * @param \Magento\Quote\Model\ShippingMethodManagement $subject
     * @param callable $proceed
     * @param Quote $quote
     * @param int $country
     * @param string $postcode
     * @param int $regionId
     * @param string $region
     * @param ExtensibleDataInterface|null $address
     * @return ShippingMethodInterface[] An array of shipping methods.
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @deprecated 100.1.6
     * @see Updated-deprication-doc-annotations
     */
    protected function aroundGetEstimatedRates(
        \Magento\Quote\Model\ShippingMethodManagement $subject,
        callable $proceed,
        Quote $quote,
        $country,
        $postcode,
        $regionId,
        $region,
        $address = null
    ) {
        if (!$address) {
            $address = $this->_addressFactory->create()
                ->setCountryId($country)
                ->setPostcode($postcode)
                ->setRegionId($regionId);
        }

        if ($this->_dataHelper->isShippingActive()) {
            return $this->getShippingMethods($quote, $address);
        }

        return $this->getShippingMethods($quote, $address);
    }

    /**
     * Get list of available shipping methods
     *
     * @param Quote $quote
     * @param ExtensibleDataInterface $address
     * @return ShippingMethodInterface[]
     */
    private function getShippingMethods(Quote $quote, $address)
    {
        $output = [];
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->addData($this->extractAddressData($address));
        $shippingAddress->setCollectShippingRates(true);

        $this->_totalsCollector->collectAddressTotals($quote, $shippingAddress);
        $quoteCustomerGroupId = $quote->getCustomerGroupId();
        $customerGroupId = $this->_customerSession->getCustomerGroupId();
        $isCustomerGroupChanged = $quoteCustomerGroupId !== $customerGroupId;
        if ($isCustomerGroupChanged) {
            $quote->setCustomerGroupId($customerGroupId);
        }
        $shippingRates = $shippingAddress->getGroupedAllShippingRates();


        $gestlogisShipPrice = null;
        if ($this->_dataHelper->isShippingActive()) {
            $gestlogisShipPrice = $this->_dataHelper->getGestLogisShipPrice();
        }
      
        

        foreach ($shippingRates as $carrierRates) {
            foreach ($carrierRates as $rate) {
                $shippingMethod = $this->_converter->modelToDataObject($rate, $quote->getQuoteCurrencyCode());

                if ($gestlogisShipPrice && $address->getPostcode() == $this->_dataHelper->getPostcodeFromSession()) {
                    if ($rate->getCarrier() == GestLogisCarrier::CODE) {
                        $output[] = $shippingMethod;
                    }
                } else if ($rate->getCarrier() !== GestLogisCarrier::CODE) {
                    $output[] = $shippingMethod;
                }
            }
        }
        if ($isCustomerGroupChanged) {
            $quote->setCustomerGroupId($quoteCustomerGroupId);
        }

        return $output;
    }

    /**
     * Get transform address interface into Array
     *
     * @param ExtensibleDataInterface $address
     * @return array
     */
    private function extractAddressData($address)
    {
        $className = \Magento\Customer\Api\Data\AddressInterface::class;
        if ($address instanceof AddressInterface) {
            $className = AddressInterface::class;
        } elseif ($address instanceof EstimateAddressInterface) {
            $className = EstimateAddressInterface::class;
        }

        $addressData = $this->getDataObjectProcessor()->buildOutputDataArray(
            $address,
            $className
        );
        unset($addressData[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);

        return $addressData;
    }

    /**
     * Gets the data object processor
     *
     * @return DataObjectProcessor
     * @deprecated 101.0.0
     * @see Updated-deprication-doc-annotations
     */
    private function getDataObjectProcessor()
    {
        if ($this->_dataProcessor === null) {
            $this->_dataProcessor = ObjectManager::getInstance()
                ->get(DataObjectProcessor::class);
        }
        return $this->_dataProcessor;
    }

    /**
     * Gets the address if exists for customer
     *
     * @param int $addressId
     * @param Quote $quote
     * @return CustomerAddress
     * @throws InputException The shipping address is incorrect.
     */
    private function getAddress(int $addressId, Quote $quote): CustomerAddress
    {
        $addresses = $quote->getCustomer()->getAddresses();
        foreach ($addresses as $address) {
            if ($addressId === (int) $address->getId()) {
                return $address;
            }
        }

        throw new InputException(__('The shipping address is missing. Set the address and try again.'));
    }
}

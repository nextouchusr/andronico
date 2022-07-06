<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Quote;

use Magento\Framework\Exception\LocalizedException;
use Magento\InventoryInStorePickupShippingApi\Model\Carrier\InStorePickup;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Nextouch\InStorePayment\Model\InStorePayment;
use Nextouch\Wins\Api\AuthManagementInterface;
use Nextouch\Wins\Api\ProductSalableQtyManagementInterface;
use Nextouch\Wins\Helper\WinsConfig;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Inventory\ProductStock;
use Nextouch\Wins\Model\Order\PickAndPayInfo;
use Nextouch\Wins\Model\Request\Auth\Authorize;
use Nextouch\Wins\Model\Request\Inventory\GetProductAvailability as GetProductAvailabilityRequest;
use Nextouch\Wins\Model\Response\Inventory\GetProductAvailability as GetProductAvailabilityResponse;
use function Lambdish\Phunctional\all;
use function Lambdish\Phunctional\first;
use function Lambdish\Phunctional\some;

class ValidateQuoteSalableQty
{
    private ProductSalableQtyManagementInterface $productSalableQtyManagement;
    private AuthManagementInterface $authManagement;
    private WinsConfig $config;
    private array $validationErrors = [];

    public function __construct(
        ProductSalableQtyManagementInterface $productSalableQtyManagement,
        AuthManagementInterface $authManagement,
        WinsConfig $config
    ) {
        $this->productSalableQtyManagement = $productSalableQtyManagement;
        $this->authManagement = $authManagement;
        $this->config = $config;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function getSpinCodeList(Quote $quote): array
    {
        $shippingAddress = $quote->getShippingAddress();
        $shippingMethod = $shippingAddress->getShippingMethod();

        // Pick&Pay or Pickup@Store
        if ($shippingMethod === InStorePickup::DELIVERY_METHOD) {
            $pickupLocationCode = $shippingAddress->getExtensionAttributes()->getPickupLocationCode();
            $paymentMethod = $quote->getPayment()->getMethod();

            // Pick&Pay
            if ($paymentMethod === InStorePayment::METHOD_CODE) {
                return [$pickupLocationCode, PickAndPayInfo::DEFAULT_PICKUP_LOCATION];
            }

            return [$pickupLocationCode, PickAndPayInfo::DEFAULT_PICKUP_LOCATION]; // Pickup@Store
        }

        return [PickAndPayInfo::DEFAULT_PICKUP_LOCATION];
    }

    /**
     * @throws LocalizedException
     */
    public function validate(Quote $quote): void
    {
        $items = $quote->getItems() ?? [];

        $isValid = some(function (string $spinCode) use ($items) {
            return all(fn(QuoteItem $item) => $this->validateItem($item, $spinCode), $items);
        }, $this->getSpinCodeList($quote));

        if (!$isValid) {
            throw new LocalizedException(first($this->getValidationErrors()));
        }
    }

    public function validateItem(QuoteItem $quoteItem, string $spinCode): bool
    {
        $getProductAvailability = $this->getProductAvailability($quoteItem, $spinCode);

        $isAvailable = some(
            fn(ProductStock $item) => $item->getAvailability() >= (int) $quoteItem->getQty(),
            $getProductAvailability->getProductList()
        );

        if (!$isAvailable) {
            $this->validationErrors[] = __('The product %1 is no longer available', $quoteItem->getName());
        }

        return $isAvailable;
    }

    private function getProductAvailability(QuoteItem $quoteItem, string $spinCode): GetProductAvailabilityResponse
    {
        $authorizeReq = new Authorize($this->config->getAuthUsername(), $this->config->getAuthPassword());
        $authorizeRes = $this->authManagement->authorize($authorizeReq);

        $loginInfo = LoginInfo::fromArray([
            'user' => $this->config->getMagentoUsername(),
            'password' => $this->config->getMagentoPassword(),
        ]);

        $getProductAvailability = new GetProductAvailabilityRequest(
            $authorizeRes->getAccessToken(),
            $loginInfo,
            $quoteItem->getSku(),
            $spinCode
        );

        return $this->productSalableQtyManagement->getProductAvailability($getProductAvailability);
    }
}

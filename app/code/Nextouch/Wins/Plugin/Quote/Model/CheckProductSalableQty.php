<?php
declare(strict_types=1);

namespace Nextouch\Wins\Plugin\Quote\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Quote\Model\QuoteManagement;
use Nextouch\Wins\Api\AuthManagementInterface;
use Nextouch\Wins\Api\ProductSalableQtyManagementInterface;
use Nextouch\Wins\Helper\WinsConfig;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Inventory\ProductStock;
use Nextouch\Wins\Model\Request\Auth\Authorize;
use Nextouch\Wins\Model\Request\Inventory\GetProductAvailability as GetProductAvailabilityRequest;
use Nextouch\Wins\Model\Response\Inventory\GetProductAvailability as GetProductAvailabilityResponse;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\some;

class CheckProductSalableQty
{
    private ProductSalableQtyManagementInterface $productSalableQtyManagement;
    private AuthManagementInterface $authManagement;
    private WinsConfig $config;

    public function __construct(
        ProductSalableQtyManagementInterface $productSalableQtyManagement,
        AuthManagementInterface $authManagement,
        WinsConfig $config
    ) {
        $this->productSalableQtyManagement = $productSalableQtyManagement;
        $this->authManagement = $authManagement;
        $this->config = $config;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeSubmit(QuoteManagement $subject, Quote $quote, array $orderData = []): array
    {
        try {
            $this->validateQuoteBeforeSubmit($quote);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__('Impossible to place order: %1', $e->getLogMessage()));
        }

        return [$quote, $orderData];
    }

    /**
     * @throws LocalizedException
     */
    private function validateQuoteBeforeSubmit(Quote $quote): void
    {
        $items = $quote->getItems() ?? [];

        each(fn(QuoteItem $item) => $this->validateProductSalableQty($item), $items);
    }

    /**
     * @throws LocalizedException
     */
    private function validateProductSalableQty(QuoteItem $quoteItem): void
    {
        $getProductAvailability = $this->getProductAvailability($quoteItem);

        $isAvailable = some(
            fn(ProductStock $item) => $item->getAvailability() >= (int) $quoteItem->getQty(),
            $getProductAvailability->getProductList()
        );

        if (!$isAvailable) {
            throw new LocalizedException(__('The product %1 is no longer available', $quoteItem->getName()));
        }
    }

    private function getProductAvailability(QuoteItem $quoteItem): GetProductAvailabilityResponse
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
            $this->getSpinCode($quoteItem)
        );

        return $this->productSalableQtyManagement->getProductAvailability($getProductAvailability);
    }

    private function getSpinCode(QuoteItem $quoteItem): string
    {
        $spinCode = $quoteItem
            ->getQuote()
            ->getShippingAddress()
            ->getExtensionAttributes()
            ->getPickupLocationCode();

        return $spinCode ?? GetProductAvailabilityRequest::DEFAULT_SPIN_CODE;
    }
}

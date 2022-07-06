<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Model\Event\AbandonedCart;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Store\Model\StoreManagerInterface;
use Nextouch\Sidea\Api\Data\InputInterface;

class Data implements InputInterface
{
    private string $subscriberKey;
    private string $emailAddress;
    private string $customerFirstName;
    private string $customerLastName;
    private string $cartId;
    private string $productId;
    private string $productImage;
    private int $productQty;
    private float $productPrice;
    private string $productName;
    private string $abandonedUrl;
    private string $abandonedAt;

    private function __construct(
        string $subscriberKey,
        string $emailAddress,
        string $customerFirstName,
        string $customerLastName,
        string $cartId,
        string $productId,
        string $productImage,
        int $productQty,
        float $productPrice,
        string $productName,
        string $abandonedUrl,
        string $abandonedAt
    ) {
        $this->subscriberKey = $subscriberKey;
        $this->emailAddress = $emailAddress;
        $this->customerFirstName = $customerFirstName;
        $this->customerLastName = $customerLastName;
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->productImage = $productImage;
        $this->productQty = $productQty;
        $this->productPrice = $productPrice;
        $this->productName = $productName;
        $this->abandonedUrl = $abandonedUrl;
        $this->abandonedAt = $abandonedAt;
    }

    public function getSubscriberKey(): string
    {
        return $this->subscriberKey;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getCustomerFirstName(): string
    {
        return $this->customerFirstName;
    }

    public function getCustomerLastName(): string
    {
        return $this->customerLastName;
    }

    public function getCartId(): string
    {
        return $this->cartId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getProductImage(): string
    {
        return $this->productImage;
    }

    public function getProductQty(): int
    {
        return $this->productQty;
    }

    public function getProductPrice(): float
    {
        return $this->productPrice;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getAbandonedUrl(): string
    {
        return $this->abandonedUrl;
    }

    public function getAbandonedAt(): string
    {
        return $this->abandonedAt;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpCastIsUnnecessaryInspection
     */
    public static function fromDomain(QuoteItem $quoteItem): self
    {
        $storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        $store = $storeManager->getStore($storeManager->getDefaultStoreView());
        $storeMediaUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'catalog/product';

        $quote = $quoteItem->getQuote();
        $customer = $quote->getCustomer();
        $product = $quoteItem->getProduct();
        $hasProductImage = $product->getThumbnail() !== 'no_selection';
        $productImage = $hasProductImage ? $storeMediaUrl . $product->getThumbnail() : '';

        return new self(
            (string) $customer->getEmail(),
            (string) $customer->getEmail(),
            (string) $customer->getFirstname(),
            (string) $customer->getLastname(),
            (string) $quote->getEntityId(),
            (string) $product->getEntityId(),
            (string) $productImage,
            (int) $quoteItem->getQty(),
            (float) $quoteItem->getRowTotalInclTax(),
            (string) $quoteItem->getName(),
            (string) $store->getUrl('checkout/cart'),
            (string) $quote->getUpdatedAt()
        );
    }

    public function toArray(): array
    {
        return [
            'SubscriberKey' => $this->getSubscriberKey(),
            'EmailAddress' => $this->getEmailAddress(),
            'customer_firstname' => $this->getCustomerFirstName(),
            'customer_lastname' => $this->getCustomerLastName(),
            'cart_id' => $this->getCartId(),
            'product_id' => $this->getProductId(),
            'product_image' => $this->getProductImage(),
            'product_qty' => $this->getProductQty(),
            'product_price' => $this->getProductPrice(),
            'product_name' => $this->getProductName(),
            'abandoned_url' => $this->getAbandonedUrl(),
            'abandoned_at' => $this->getAbandonedAt(),
        ];
    }
}

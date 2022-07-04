<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Model\Event\AbandonedCart;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use Nextouch\Sidea\Api\Data\InputInterface;

class Data implements InputInterface
{
    private string $subscriberKey;
    private string $emailAddress;
    private string $id;
    private string $customerFirstName;
    private string $customerLastName;
    private int $itemsQty;
    private float $itemsPrice;
    private string $itemsName;

    private function __construct(
        string $subscriberKey,
        string $emailAddress,
        string $id,
        string $customerFirstName,
        string $customerLastName,
        int $itemsQty,
        float $itemsPrice,
        string $itemsName
    ) {
        $this->subscriberKey = $subscriberKey;
        $this->emailAddress = $emailAddress;
        $this->id = $id;
        $this->customerFirstName = $customerFirstName;
        $this->customerLastName = $customerLastName;
        $this->itemsQty = $itemsQty;
        $this->itemsPrice = $itemsPrice;
        $this->itemsName = $itemsName;
    }

    public function getSubscriberKey(): string
    {
        return $this->subscriberKey;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomerFirstName(): string
    {
        return $this->customerFirstName;
    }

    public function getCustomerLastName(): string
    {
        return $this->customerLastName;
    }

    public function getItemsQty(): int
    {
        return $this->itemsQty;
    }

    public function getItemsPrice(): float
    {
        return $this->itemsPrice;
    }

    public function getItemsName(): string
    {
        return $this->itemsName;
    }

    /**
     * @noinspection PhpCastIsUnnecessaryInspection
     */
    public static function fromDomain(QuoteItem $quoteItem): self
    {
        $quote = $quoteItem->getQuote();
        $customer = $quote->getCustomer();

        return new self(
            (string) $customer->getEmail(),
            (string) $customer->getEmail(),
            (string) $quote->getEntityId(),
            (string) $customer->getFirstname(),
            (string) $customer->getLastname(),
            (int) $quoteItem->getQty(),
            (float) $quoteItem->getRowTotalInclTax(),
            (string) $quoteItem->getName(),
        );
    }

    public function toArray(): array
    {
        return [
            'SubscriberKey' => $this->getSubscriberKey(),
            'EmailAddress' => $this->getEmailAddress(),
            'Id' => $this->getId(),
            'customer_firstname' => $this->getCustomerFirstName(),
            'customer_lastname' => $this->getCustomerLastName(),
            'items_qty' => $this->getItemsQty(),
            'items_price' => $this->getItemsPrice(),
            'items_name' => $this->getItemsName(),
        ];
    }
}

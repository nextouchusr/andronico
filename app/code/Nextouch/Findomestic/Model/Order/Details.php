<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Order;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Magento\Quote\Api\Data\CartItemInterface;
use Nextouch\Findomestic\Api\Data\ArrayableInterface;
use Nextouch\Findomestic\Model\Customer\Details as Customer;
use Nextouch\Findomestic\Model\Installment\Item;
use Nextouch\Findomestic\Model\Plan\Details as Plan;
use Nextouch\Quote\Api\Data\CartInterface;
use function Lambdish\Phunctional\map;

class Details implements ArrayableInterface
{
    private const DEFAULT_CHANNEL = 'B2C';

    private ?string $orderNumber;
    private ?string $orderChannel;
    private ?Plan $plan;
    private ?Customer $customer;
    private Collection $installmentItems;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        ?string $orderNumber = null,
        ?string $orderChannel = null,
        ?Plan $plan = null,
        ?Customer $customer = null,
        array $installmentItems = []
    ) {
        $this->orderNumber = $orderNumber;
        $this->orderChannel = $orderChannel;
        $this->plan = $plan;
        $this->customer = $customer;
        $this->installmentItems = new Collection(Item::class, $installmentItems);
    }

    /**
     * @return string|null
     */
    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    /**
     * @return string|null
     */
    public function getOrderChannel(): ?string
    {
        return $this->orderChannel;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Plan\Details|null
     */
    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Customer\Details|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Installment\Item[]
     */
    public function getInstallmentItems(): array
    {
        return $this->installmentItems->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromDomain(CartInterface $quote): self
    {
        $installmentItems = map(fn(CartItemInterface $item) => Item::fromDomain($item), $quote->getItems());

        return new self(
            $quote->getReservedOrderId(),
            self::DEFAULT_CHANNEL,
            Plan::fromDomain($quote),
            Customer::fromDomain($quote),
            $installmentItems
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        $installmentItems = map(fn(array $item) => Item::fromArray($item), (array) $data['installmentItems']);

        return new self(
            isset($data['orderNumber']) ? (string) $data['orderNumber'] : null,
            isset($data['orderChannel']) ? (string) $data['orderChannel'] : null,
            isset($data['plan']) ? Plan::fromArray($data['plan']) : null,
            isset($data['customerData']) ? Customer::fromArray($data['customerData']) : null,
            $installmentItems
        );
    }

    public function toArray(): array
    {
        $arr = [
            'orderNumber' => $this->getOrderNumber(),
            'orderChannel' => $this->getOrderChannel(),
            'plan' => $this->getPlan()->toArray(),
            'customerData' => $this->getCustomer()->toArray(),
            'installmentItems' => $this->installmentItems->map(fn(Item $item) => $item->toArray())->toArray(),
        ];

        return array_filter($arr);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Order;

use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\Wins\Api\Data\ArrayableInterface;

class PickAndPayInfo implements ArrayableInterface
{
    private const DEFAULT_PICKUP_LOCATION = 'ESL';
    private const STANDARD_TYPE = 'standard';
    private const EXTENDED_TYPE = 'extended';

    private bool $isPickAndPay;
    private string $pickAndPaySellPoint;
    private string $pickAndPayType;

    public function __construct(
        bool $isPickAndPay,
        string $pickAndPaySellPoint,
        string $pickAndPayType
    ) {
        $this->isPickAndPay = $isPickAndPay;
        $this->pickAndPaySellPoint = $pickAndPaySellPoint;
        $this->pickAndPayType = $pickAndPayType;
    }

    public function isPickAndPay(): bool
    {
        return $this->isPickAndPay;
    }

    public function getPickAndPaySellPoint(): string
    {
        return $this->pickAndPaySellPoint;
    }

    public function getPickAndPayType(): string
    {
        return $this->pickAndPayType;
    }

    public static function fromDomain(OrderInterface $order): self
    {
        $isPickAndPay = (bool) $order->getExtensionAttributes()->getPickupLocationCode();
        $pickupLocationCode = (string) $order->getExtensionAttributes()->getPickupLocationCode();
        $isDefaultPickupLocation = $pickupLocationCode === self::DEFAULT_PICKUP_LOCATION;
        $pickAndPayType = $isPickAndPay ? ($isDefaultPickupLocation ? self::EXTENDED_TYPE : self::STANDARD_TYPE) : '';

        return new self($isPickAndPay, $pickupLocationCode, $pickAndPayType);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (bool) $data['is_pick_and_pay'],
            (string) $data['pick_and_pay_sell_point'],
            (string) $data['pick_and_pay_type']
        );
    }

    public function toArray(): array
    {
        return [
            'is_pick_and_pay' => $this->isPickAndPay(),
            'pick_and_pay_sell_point' => $this->getPickAndPaySellPoint(),
            'pick_and_pay_type' => $this->getPickAndPayType(),
        ];
    }
}

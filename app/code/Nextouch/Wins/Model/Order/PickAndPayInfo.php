<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Order;

use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\Wins\Api\Data\ArrayableInterface;

class PickAndPayInfo implements ArrayableInterface
{
    private bool $isPickAndPay;
    private string $pickAndPaySellPoint;

    public function __construct(bool $isPickAndPay, string $pickAndPaySellPoint)
    {
        $this->isPickAndPay = $isPickAndPay;
        $this->pickAndPaySellPoint = $pickAndPaySellPoint;
    }

    public function isPickAndPay(): bool
    {
        return $this->isPickAndPay;
    }

    public function getPickAndPaySellPoint(): string
    {
        return $this->pickAndPaySellPoint;
    }

    public static function fromDomain(OrderInterface $order): self
    {
        return new self(
            (bool) $order->getExtensionAttributes()->getPickupLocationCode(),
            (string) $order->getExtensionAttributes()->getPickupLocationCode()
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (bool) $data['is_pick_and_pay'],
            (string) $data['pick_and_pay_sell_point']
        );
    }

    public function toArray(): array
    {
        return [
            'is_pick_and_pay' => $this->isPickAndPay(),
            'pick_and_pay_sell_point' => $this->getPickAndPaySellPoint(),
        ];
    }
}

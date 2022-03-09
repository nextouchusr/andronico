<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Order;

use Nextouch\Sales\Api\Data\OrderInterface;

class PickAndPayInfo
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
}

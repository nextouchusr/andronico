<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Request\Installment;

use Nextouch\Findomestic\Api\Data\InputInterface;

class Cancel implements InputInterface
{
    private string $issuerInstallmentId;
    private float $amount;
    private string $payerId;
    private string $orderNumber;
    private bool $isComplete;
    private string $reason;

    public function __construct(
        string $issuerInstallmentId,
        float $amount,
        string $payerId,
        string $orderNumber,
        bool $isComplete,
        string $reason
    ) {
        $this->issuerInstallmentId = $issuerInstallmentId;
        $this->amount = $amount;
        $this->payerId = $payerId;
        $this->orderNumber = $orderNumber;
        $this->isComplete = $isComplete;
        $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getIssuerInstallmentId(): string
    {
        return $this->issuerInstallmentId;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getPayerId(): string
    {
        return $this->payerId;
    }

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    public function toArray(): array
    {
        $arr = [
            'amount' => $this->getAmount(),
            'payerId' => $this->getPayerId(),
            'orderNumber' => $this->getOrderNumber(),
            'complete' => $this->isComplete(),
            'reason' => $this->getReason(),
        ];

        return array_filter($arr);
    }
}

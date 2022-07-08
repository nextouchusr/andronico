<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Request\Installment;

use Nextouch\Findomestic\Api\Data\InputInterface;

class Refund implements InputInterface
{
    private string $issuerInstallmentId;
    private string $orderNumber;
    private float $amount;
    private float $financedAmount;
    private bool $isComplete;
    private string $notificationId;
    private string $payerId;
    private string $reason;

    public function __construct(
        string $issuerInstallmentId,
        string $orderNumber,
        float $amount,
        float $financedAmount,
        bool $isComplete,
        string $notificationId,
        string $payerId,
        string $reason
    ) {
        $this->issuerInstallmentId = $issuerInstallmentId;
        $this->orderNumber = $orderNumber;
        $this->amount = $amount;
        $this->financedAmount = $financedAmount;
        $this->isComplete = $isComplete;
        $this->notificationId = $notificationId;
        $this->payerId = $payerId;
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
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function getFinancedAmount(): float
    {
        return $this->financedAmount;
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
    public function getNotificationId(): string
    {
        return $this->notificationId;
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
    public function getReason(): string
    {
        return $this->reason;
    }

    public function toArray(): array
    {
        return [
            'orderNumber' => $this->getOrderNumber(),
            'amount' => $this->getAmount(),
            'financedAmount' => $this->getFinancedAmount(),
            'complete' => $this->isComplete(),
            'notificationId' => $this->getNotificationId(),
            'payerId' => $this->getPayerId(),
            'reason' => $this->getReason(),
        ];
    }
}

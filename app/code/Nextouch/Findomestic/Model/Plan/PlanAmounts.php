<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Plan;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;
use Nextouch\Quote\Api\Data\CartInterface;

class PlanAmounts implements ArrayableInterface
{
    private ?float $total;
    private ?float $perMonth;
    private ?float $firstMonth;
    private ?float $lastMonth;
    private ?float $tradeInAmount;
    private ?float $interestAmount;
    private ?float $totalAndInterestAmount;
    private ?float $downPayment;

    public function __construct(
        ?float $total = 0.0,
        ?float $perMonth = 0.0,
        ?float $firstMonth = 0.0,
        ?float $lastMonth = 0.0,
        ?float $tradeInAmount = 0.0,
        ?float $interestAmount = 0.0,
        ?float $totalAndInterestAmount = 0.0,
        ?float $downPayment = 0.0
    ) {
        $this->total = $total;
        $this->perMonth = $perMonth;
        $this->firstMonth = $firstMonth;
        $this->lastMonth = $lastMonth;
        $this->tradeInAmount = $tradeInAmount;
        $this->interestAmount = $interestAmount;
        $this->totalAndInterestAmount = $totalAndInterestAmount;
        $this->downPayment = $downPayment;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @return float|null
     */
    public function getPerMonth(): ?float
    {
        return $this->perMonth;
    }

    /**
     * @return float|null
     */
    public function getFirstMonth(): ?float
    {
        return $this->firstMonth;
    }

    /**
     * @return float|null
     */
    public function getLastMonth(): ?float
    {
        return $this->lastMonth;
    }

    /**
     * @return float|null
     */
    public function getTradeInAmount(): ?float
    {
        return $this->tradeInAmount;
    }

    /**
     * @return float|null
     */
    public function getInterestAmount(): ?float
    {
        return $this->interestAmount;
    }

    /**
     * @return float|null
     */
    public function getTotalAndInterestAmount(): ?float
    {
        return $this->totalAndInterestAmount;
    }

    /**
     * @return float|null
     */
    public function getDownPayment(): ?float
    {
        return $this->downPayment;
    }

    public static function fromDomain(CartInterface $quote): self
    {
        return new self($quote->getGrandTotal());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['total']) ? (float) $data['total'] : null,
            isset($data['perMonth']) ? (float) $data['perMonth'] : null,
            isset($data['firstMonth']) ? (float) $data['firstMonth'] : null,
            isset($data['lastMonth']) ? (float) $data['lastMonth'] : null,
            isset($data['tradeInAmount']) ? (float) $data['tradeInAmount'] : null,
            isset($data['interestAmount']) ? (float) $data['interestAmount'] : null,
            isset($data['totalAndInterestAmount']) ? (float) $data['totalAndInterestAmount'] : null,
            isset($data['downPayment']) ? (float) $data['downPayment'] : null
        );
    }

    public function toArray(): array
    {
        $arr = [
            'total' => $this->getTotal(),
            'perMonth' => $this->getPerMonth(),
            'firstMonth' => $this->getFirstMonth(),
            'lastMonth' => $this->getLastMonth(),
            'tradeInAmount' => $this->getTradeInAmount(),
            'interestAmount' => $this->getInterestAmount(),
            'totalAndInterestAmount' => $this->getTotalAndInterestAmount(),
            'downPayment' => $this->getDownPayment(),
        ];

        return array_filter($arr);
    }
}

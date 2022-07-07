<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Plan;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;
use Nextouch\Quote\Api\Data\CartInterface;

class Details implements ArrayableInterface
{
    private const DEFAULT_PLAN_TYPE = 'DEFAULT';

    private ?Program $program;
    private ?PlanAmounts $planAmounts;
    private ?string $planType;

    public function __construct(
        ?Program $program = null,
        ?PlanAmounts $planAmounts = null,
        ?string $planType = null
    ) {
        $this->program = $program;
        $this->planAmounts = $planAmounts;
        $this->planType = $planType;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Plan\Program|null
     */
    public function getProgram(): ?Program
    {
        return $this->program;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Plan\PlanAmounts|null
     */
    public function getPlanAmounts(): ?PlanAmounts
    {
        return $this->planAmounts;
    }

    /**
     * @return string|null
     */
    public function getPlanType(): ?string
    {
        return $this->planType;
    }

    public static function fromDomain(CartInterface $quote): self
    {
        return new self(
            Program::asDefault(),
            PlanAmounts::fromDomain($quote),
            self::DEFAULT_PLAN_TYPE
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['program']) ? Program::fromArray($data['program']) : null,
            isset($data['planAmounts']) ? PlanAmounts::fromArray($data['planAmounts']) : null,
            isset($data['planType']) ? (string) $data['planType'] : null
        );
    }

    public function toArray(): array
    {
        $arr = [
            'program' => $this->getProgram()->toArray(),
            'planAmounts' => $this->getPlanAmounts()->toArray(),
            'planType' => $this->getPlanType(),
        ];

        return array_filter($arr);
    }
}

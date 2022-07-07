<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Installment;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;
use Nextouch\Findomestic\Model\Plan\Details as Plan;

class Details implements ArrayableInterface
{
    private ?string $issuerInstallmentId;
    private ?string $status;
    private ?Plan $plan;

    public function __construct(
        ?string $issuerInstallmentId = null,
        ?string $status = null,
        ?Plan $plan = null
    ) {
        $this->issuerInstallmentId = $issuerInstallmentId;
        $this->status = $status;
        $this->plan = $plan;
    }

    /**
     * @return string|null
     */
    public function getIssuerInstallmentId(): ?string
    {
        return $this->issuerInstallmentId;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Plan\Details|null
     */
    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['issuerInstallmentId']) ? (string) $data['issuerInstallmentId'] : null,
            isset($data['status']) ? (string) $data['status'] : null,
            isset($data['plan']) ? Plan::fromArray($data['plan']) : null
        );
    }

    public function toArray(): array
    {
        $arr = [
            'issuerInstallmentId' => $this->getIssuerInstallmentId(),
            'status' => $this->getStatus(),
            'plan' => $this->getPlan()->toArray(),
        ];

        return array_filter($arr);
    }
}

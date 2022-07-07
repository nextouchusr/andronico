<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Response\Installment;

use Nextouch\Findomestic\Api\Data\OutputInterface;
use Nextouch\Findomestic\Model\Common\StatusReturn;
use Nextouch\Findomestic\Model\Installment\Details as Installment;

class Cancel implements OutputInterface
{
    private StatusReturn $statusReturn;
    private string $transactionTimeStamp;
    private ?Installment $installment;

    public function __construct(
        StatusReturn $statusReturn,
        string $transactionTimeStamp,
        ?Installment $installment
    ) {
        $this->statusReturn = $statusReturn;
        $this->transactionTimeStamp = $transactionTimeStamp;
        $this->installment = $installment;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Common\StatusReturn
     */
    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    /**
     * @return string
     */
    public function getTransactionTimeStamp(): string
    {
        return $this->transactionTimeStamp;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Installment\Details|null
     */
    public function getInstallment(): ?Installment
    {
        return $this->installment;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            StatusReturn::success(),
            (string) $data['transactionTimeStamp'],
            Installment::fromArray($data['installment'])
        );
    }

    public static function fromError(array $data): self
    {
        return new self(
            StatusReturn::error($data),
            '',
            null
        );
    }
}

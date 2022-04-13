<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Response\Installment;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;
use Nextouch\Findomestic\Api\Data\OutputInterface;
use Nextouch\Findomestic\Model\Common\StatusReturn;

class Create implements ArrayableInterface, OutputInterface
{
    private StatusReturn $statusReturn;
    private string $applyURL;
    private string $applicationId;

    public function __construct(
        StatusReturn $statusReturn,
        string $applyURL,
        string $applicationId
    ) {
        $this->statusReturn = $statusReturn;
        $this->applyURL = $applyURL;
        $this->applicationId = $applicationId;
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
    public function getApplyURL(): string
    {
        return $this->applyURL;
    }

    /**
     * @return string
     */
    public function getApplicationId(): string
    {
        return $this->applicationId;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            StatusReturn::success(),
            (string) $data['applyURL'],
            (string) $data['applicationId']
        );
    }

    public static function fromError(array $data): self
    {
        return new self(
            StatusReturn::error($data),
            '',
            ''
        );
    }

    public function toArray(): array
    {
        return [
            'statusReturn' => $this->getStatusReturn()->toArray(),
            'applyURL' => $this->getApplyURL(),
            'applicationId' => $this->getApplicationId(),
        ];
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Response\Installment;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;
use Nextouch\Findomestic\Api\Data\OutputInterface;
use Nextouch\Findomestic\Model\Common\StatusReturn;

class Refresh implements ArrayableInterface, OutputInterface
{
    private StatusReturn $statusReturn;
    private string $applyURL;

    public function __construct(StatusReturn $statusReturn, string $applyURL)
    {
        $this->statusReturn = $statusReturn;
        $this->applyURL = $applyURL;
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

    public static function fromArray(array $data): self
    {
        return new self(
            StatusReturn::success(),
            (string) $data['applyURL']
        );
    }

    public static function fromError(array $data): self
    {
        return new self(
            StatusReturn::error($data),
            ''
        );
    }

    public function toArray(): array
    {
        return [
            'statusReturn' => $this->getStatusReturn()->toArray(),
            'applyURL' => $this->getApplyURL(),
        ];
    }
}

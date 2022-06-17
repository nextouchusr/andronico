<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Response\Installment;

use Nextouch\Findomestic\Api\Data\OutputInterface;
use Nextouch\Findomestic\Model\Common\StatusReturn;

class Refund implements OutputInterface
{
    private StatusReturn $statusReturn;

    public function __construct(StatusReturn $statusReturn)
    {
        $this->statusReturn = $statusReturn;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Common\StatusReturn
     */
    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public static function fromArray(array $data): self
    {
        return new self(StatusReturn::success());
    }

    public static function fromError(array $data): self
    {
        return new self(StatusReturn::error($data));
    }
}

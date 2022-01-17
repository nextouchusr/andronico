<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Common;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class StatusReturn implements OutputInterface
{
    private bool $isOk;
    private int $errorCode;
    private string $errorDescription;

    private function __construct(
        bool $isOk,
        int $errorCode,
        string $errorDescription
    ) {
        $this->isOk = $isOk;
        $this->errorCode = $errorCode;
        $this->errorDescription = $errorDescription;
    }

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->isOk;
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorDescription(): string
    {
        return $this->errorDescription;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $isOk = (bool) $propertyAccessor->getValue($object, 'ok');
        $errorCode = (int) $propertyAccessor->getValue($object, 'error_code');
        $errorDescription = (string) $propertyAccessor->getValue($object, 'error_descr');

        return new self($isOk, $errorCode, $errorDescription);
    }
}

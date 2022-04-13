<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Common;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;

class StatusReturn implements ArrayableInterface
{
    private bool $isOk;
    private int $errorCode;
    private string $errorDescription;

    public function __construct(
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

    public static function success(): self
    {
        return new self(true, 0, '');
    }

    public static function error(array $data): self
    {
        return new self(
            false,
            (int) $data['errorCode'],
            (string) $data['errorDescription']
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (bool) $data['isOk'],
            (int) $data['errorCode'],
            (string) $data['errorDescription']
        );
    }

    public function toArray(): array
    {
        return [
            'isOk' => $this->isOk(),
            'errorCode' => $this->getErrorCode(),
            'errorDescription' => $this->getErrorDescription(),
        ];
    }
}

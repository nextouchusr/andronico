<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Common;

class StatusReturn
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

    public function isOk(): bool
    {
        return $this->isOk;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

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
}

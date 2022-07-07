<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Response\Auth;

use Nextouch\Wins\Api\Data\OutputInterface;
use Nextouch\Wins\Model\Common\StatusReturn;

class Authorize implements OutputInterface
{
    private StatusReturn $statusReturn;
    private string $accessToken;
    private string $expireDateTime;

    private function __construct(
        StatusReturn $statusReturn,
        string $accessToken,
        string $expireDateTime
    ) {
        $this->statusReturn = $statusReturn;
        $this->accessToken = $accessToken;
        $this->expireDateTime = $expireDateTime;
    }

    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getExpireDateTime(): string
    {
        return $this->expireDateTime;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            StatusReturn::success(),
            (string) $data['accessToken'],
            (string) $data['expireDateTime']
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
}

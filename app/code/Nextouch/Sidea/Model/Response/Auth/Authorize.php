<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Model\Response\Auth;

use Nextouch\Sidea\Api\Data\OutputInterface;
use Nextouch\Sidea\Model\Common\StatusReturn;

class Authorize implements OutputInterface
{
    private StatusReturn $statusReturn;
    private string $accessToken;
    private string $tokenType;
    private int $expiresIn;
    private string $scope;
    private string $soapInstanceUrl;
    private string $restInstanceUrl;

    private function __construct(
        StatusReturn $statusReturn,
        string $accessToken,
        string $tokenType,
        int $expiresIn,
        string $scope,
        string $soapInstanceUrl,
        string $restInstanceUrl
    ) {
        $this->statusReturn = $statusReturn;
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
        $this->scope = $scope;
        $this->soapInstanceUrl = $soapInstanceUrl;
        $this->restInstanceUrl = $restInstanceUrl;
    }

    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getSoapInstanceUrl(): string
    {
        return $this->soapInstanceUrl;
    }

    public function getRestInstanceUrl(): string
    {
        return $this->restInstanceUrl;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            StatusReturn::success(),
            (string) $data['access_token'],
            (string) $data['token_type'],
            (int) $data['expires_in'],
            (string) $data['scope'],
            (string) $data['soap_instance_url'],
            (string) $data['rest_instance_url']
        );
    }

    public static function fromError(array $data): self
    {
        return new self(
            StatusReturn::error($data),
            '',
            '',
            0,
            '',
            '',
            ''
        );
    }
}

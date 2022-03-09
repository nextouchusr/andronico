<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Response\Auth;

use Nextouch\Wins\Api\Data\OutputInterface;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Common\StatusReturn;

class Authorize implements OutputInterface
{
    private StatusReturn $statusReturn;
    private string $accessToken;
    private string $expireDateTime;
    private string $user;
    private string $ip;
    private string $application;
    private LoginInfo $loginInfo;

    private function __construct(
        StatusReturn $statusReturn,
        string $accessToken,
        string $expireDateTime,
        string $user,
        string $ip,
        string $application,
        LoginInfo $loginInfo
    ) {
        $this->statusReturn = $statusReturn;
        $this->accessToken = $accessToken;
        $this->expireDateTime = $expireDateTime;
        $this->user = $user;
        $this->ip = $ip;
        $this->application = $application;
        $this->loginInfo = $loginInfo;
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

    public function getUser(): string
    {
        return $this->user;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getApplication(): string
    {
        return $this->application;
    }

    public function getLoginInfo(): LoginInfo
    {
        return $this->loginInfo;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            StatusReturn::success(),
            (string) $data['accessToken'],
            (string) $data['expireDateTime'],
            (string) $data['user'],
            (string) $data['ip'],
            (string) $data['application'],
            LoginInfo::fromArray($data['login_info'])
        );
    }

    public static function fromError(array $data): self
    {
        return new self(
            StatusReturn::error($data),
            '',
            '',
            '',
            '',
            '',
            LoginInfo::asEmpty()
        );
    }
}

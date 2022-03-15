<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Request\Order;

use Nextouch\Wins\Api\Data\InputInterface;
use Nextouch\Wins\Model\Auth\LoginInfo;

class UpdateOrderState implements InputInterface
{
    private string $accessToken;
    private LoginInfo $loginInfo;
    private string $incrementId;
    private string $state;

    public function __construct(
        string $accessToken,
        LoginInfo $loginInfo,
        string $incrementId,
        string $state
    ) {
        $this->accessToken = $accessToken;
        $this->loginInfo = $loginInfo;
        $this->incrementId = $incrementId;
        $this->state = $state;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getLoginInfo(): LoginInfo
    {
        return $this->loginInfo;
    }

    public function getIncrementId(): string
    {
        return $this->incrementId;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function toArray(): array
    {
        return [
            'increment_id' => $this->getIncrementId(),
            'state' => $this->getState(),
            'login_info' => $this->getLoginInfo()->toArray(),
        ];
    }
}

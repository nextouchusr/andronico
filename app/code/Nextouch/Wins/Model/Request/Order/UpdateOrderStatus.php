<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Request\Order;

use Nextouch\Wins\Api\Data\InputInterface;
use Nextouch\Wins\Model\Auth\LoginInfo;

class UpdateOrderStatus implements InputInterface
{
    private string $accessToken;
    private LoginInfo $loginInfo;
    private string $incrementId;
    private string $status;
    private ?string $transactionID;
    private ?string $trackingLink;

    public function __construct(
        string $accessToken,
        LoginInfo $loginInfo,
        string $incrementId,
        string $status,
        ?string $transactionID = null,
        ?string $trackingLink = null
    ) {
        $this->accessToken = $accessToken;
        $this->loginInfo = $loginInfo;
        $this->incrementId = $incrementId;
        $this->status = $status;
        $this->transactionID = $transactionID;
        $this->trackingLink = $trackingLink;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTransactionID(): ?string
    {
        return $this->transactionID;
    }

    public function getTrackingLink(): ?string
    {
        return $this->trackingLink;
    }

    public function toArray(): array
    {
        $arr = [
            'increment_id' => $this->getIncrementId(),
            'status' => $this->getStatus(),
            'transactionID' => $this->getTransactionID(),
            'trackingLink' => $this->getTrackingLink(),
            'login_info' => $this->getLoginInfo()->toArray(),
        ];

        return array_filter($arr);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Request\Inventory;

use Nextouch\Wins\Api\Data\InputInterface;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Order\PickAndPayInfo;

class GetProductAvailability implements InputInterface
{
    private string $accessToken;
    private LoginInfo $loginInfo;
    private string $productCode;
    private string $spinCode;

    public function __construct(
        string $accessToken,
        LoginInfo $loginInfo,
        string $productCode,
        string $spinCode = PickAndPayInfo::DEFAULT_PICKUP_LOCATION
    ) {
        $this->accessToken = $accessToken;
        $this->loginInfo = $loginInfo;
        $this->productCode = $productCode;
        $this->spinCode = $spinCode;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getLoginInfo(): LoginInfo
    {
        return $this->loginInfo;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function getSpinCode(): string
    {
        return $this->spinCode;
    }

    public function toArray(): array
    {
        return [
            'ProductCode' => $this->getProductCode(),
            'spinCode' => $this->getSpinCode(),
            'login_info' => $this->getLoginInfo()->toArray(),
        ];
    }
}

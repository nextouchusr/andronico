<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Request\Customer;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\App\ObjectManager;
use Nextouch\Wins\Api\Data\InputInterface;
use Nextouch\Wins\Model\Auth\LoginInfo;

class UpsertCustomer implements InputInterface
{
    private string $accessToken;
    private LoginInfo $loginInfo;
    private CustomerInterface $customer;

    public function __construct(string $accessToken, LoginInfo $loginInfo, CustomerInterface $customer)
    {
        $this->accessToken = $accessToken;
        $this->loginInfo = $loginInfo;
        $this->customer = $customer;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getLoginInfo(): LoginInfo
    {
        return $this->loginInfo;
    }

    public function getCustomer(): CustomerInterface
    {
        return $this->customer;
    }

    public function toArray(): array
    {
        $dataObjectConverter = ObjectManager::getInstance()->create(ExtensibleDataObjectConverter::class);
        $customer = $dataObjectConverter->toNestedArray($this->getCustomer(), [], CustomerInterface::class);
        $customer['is_privacy_policy_accepted'] = (bool) ($customer['is_privacy_policy_accepted'] ?? false);
        $customer['is_web_profiling_accepted'] = (bool) ($customer['is_web_profiling_accepted'] ?? false);

        return array_merge(
            $customer,
            ['login_info' => $this->getLoginInfo()->toArray()]
        );
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Request\Customer;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\AttributeInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\App\ObjectManager;
use Nextouch\Wins\Api\Data\InputInterface;
use Nextouch\Wins\Model\Auth\LoginInfo;
use function Lambdish\Phunctional\map;

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
        $customer['addresses'] = map(function (AddressInterface $address, int $index) use ($customer) {
            $attributes = map(fn(AttributeInterface $attribute) => [
                'attribute_code' => $attribute->getAttributeCode(),
                'value' => $attribute->getValue(),
            ], $address->getCustomAttributes());
            $customer['addresses'][$index]['custom_attributes'] = array_values($attributes);

            return $customer['addresses'][$index];
        }, $this->getCustomer()->getAddresses());

        return array_merge(
            $customer,
            ['login_info' => $this->getLoginInfo()->toArray()]
        );
    }
}

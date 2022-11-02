<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Plugin\Webapi\Controller\Rest;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Webapi\Controller\Rest\InputParamsResolver;

class WrapFieldsForCustomerProfiling
{
    private const CREATE_CUSTOMER_METHOD = 'createAccount';
    private const UPDATE_CUSTOMER_METHOD = 'save';

    public function afterGetInputData(InputParamsResolver $subject, array $result): array
    {
        if (!$this->canWrap($subject)) {
            return $result;
        }

        $customer = $result['customer'];
        if (isset($customer['is_privacy_policy_accepted'])) {
            $customer['custom_attributes'][] = [
                'attribute_code' => 'is_privacy_policy_accepted',
                'value' => (int) ($customer['is_privacy_policy_accepted'] ?? 0),
            ];
        }

        if (isset($customer['is_web_profiling_accepted'])) {
            $customer['custom_attributes'][] = [
                'attribute_code' => 'is_web_profiling_accepted',
                'value' => (int) ($customer['is_web_profiling_accepted'] ?? 0),
            ];
        }

        if (isset($customer['is_subscribed'])) {
            $customer['extension_attributes']['is_subscribed'] = (bool) ($customer['is_subscribed'] ?? false);
        }

        unset($customer['is_web_profiling_accepted']);
        unset($customer['is_privacy_policy_accepted']);
        unset($customer['is_subscribed']);

        $result['customer'] = $customer;

        return $result;
    }

    private function canWrap(InputParamsResolver $subject): bool
    {
        return $this->shouldCreateCustomer($subject) || $this->shouldUpdateCustomer($subject);
    }

    private function shouldCreateCustomer(InputParamsResolver $subject): bool
    {
        return (
            $subject->getRoute()->getServiceClass() === AccountManagementInterface::class &&
            $subject->getRoute()->getServiceMethod() === self::CREATE_CUSTOMER_METHOD
        );
    }

    private function shouldUpdateCustomer(InputParamsResolver $subject): bool
    {
        return (
            $subject->getRoute()->getServiceClass() === CustomerRepositoryInterface::class &&
            $subject->getRoute()->getServiceMethod() === self::UPDATE_CUSTOMER_METHOD
        );
    }
}

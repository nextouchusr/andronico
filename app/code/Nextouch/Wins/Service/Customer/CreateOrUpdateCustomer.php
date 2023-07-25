<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Data\Customer;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Customer\Api\Data\CustomerInterface;
use Nextouch\Wins\Api\AuthManagementInterface;
use Nextouch\Wins\Api\CustomerRepositoryInterface as WinsCustomerRepositoryInterface;
use Nextouch\Wins\Helper\WinsConfig;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Request\Auth\Authorize;
use Nextouch\Wins\Model\Request\Customer\UpsertCustomer;

class CreateOrUpdateCustomer
{
    private CustomerRepositoryInterface $customerRepository;
    private AuthManagementInterface $authManagement;
    private WinsCustomerRepositoryInterface $winsCustomerRepository;
    private WinsConfig $config;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        AuthManagementInterface $authManagement,
        WinsCustomerRepositoryInterface $winsCustomerRepository,
        WinsConfig $config
    ) {
        $this->customerRepository = $customerRepository;
        $this->authManagement = $authManagement;
        $this->winsCustomerRepository = $winsCustomerRepository;
        $this->config = $config;
    }

    /**
     * @throws LocalizedException
     */
    public function upsert(Customer $customer): void
    {
        $isSuccess = $this->upsertCustomer($customer);

        if ($isSuccess) {
            $customer->setCustomAttribute(CustomerInterface::CUSTOMER_SYNC_FAILURES, 0);
        } else {
            //$failures = (int) $customer->getCustomAttribute(CustomerInterface::CUSTOMER_SYNC_FAILURES)->getValue();
            //$customer->setCustomAttribute(CustomerInterface::CUSTOMER_SYNC_FAILURES, ++$failures);

            $failures = $customer->getCustomAttribute(CustomerInterface::CUSTOMER_SYNC_FAILURES);
            if ($failures && $failures->getValue()) {
                $failures = (int) $failures->getValue();
                $customer->setCustomAttribute(CustomerInterface::CUSTOMER_SYNC_FAILURES, ++$failures);
            }
        }

        $this->customerRepository->save($customer);
    }

    private function upsertCustomer(Customer $customer): bool
    {
        $authorizeReq = new Authorize($this->config->getAuthUsername(), $this->config->getAuthPassword());
        $authorizeRes = $this->authManagement->authorize($authorizeReq);

        $loginInfo = LoginInfo::fromArray([
            'user' => $this->config->getMagentoUsername(),
            'password' => $this->config->getMagentoPassword(),
        ]);

        $upsertCustomer = new UpsertCustomer(
            $authorizeRes->getAccessToken(),
            $loginInfo,
            $customer
        );

        return $this->winsCustomerRepository->upsert($upsertCustomer);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Data\Customer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Nextouch\Wins\Service\Customer\CreateOrUpdateCustomer as CreateOrUpdateCustomerService;

class UpdateCustomer implements ObserverInterface
{
    private CustomerRepositoryInterface $customerRepository;
    private CreateOrUpdateCustomerService $createOrUpdateCustomerService;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CreateOrUpdateCustomerService $createOrUpdateCustomerService
    ) {
        $this->customerRepository = $customerRepository;
        $this->createOrUpdateCustomerService = $createOrUpdateCustomerService;
    }

    public function execute(Observer $observer): void
    {
        $email = $observer->getData('email');

        /** @var Customer $customer */
        $customer = $this->customerRepository->get($email);

        $this->createOrUpdateCustomerService->upsert($customer);
    }
}

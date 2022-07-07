<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer;

use Magento\Customer\Model\Data\Customer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Nextouch\Wins\Service\Customer\CreateOrUpdateCustomer as CreateOrUpdateCustomerService;

class CreateCustomer implements ObserverInterface
{
    private CreateOrUpdateCustomerService $createOrUpdateCustomerService;

    public function __construct(CreateOrUpdateCustomerService $createOrUpdateCustomerService)
    {
        $this->createOrUpdateCustomerService = $createOrUpdateCustomerService;
    }

    public function execute(Observer $observer): void
    {
        /** @var Customer $customer */
        $customer = $observer->getData('customer');

        $this->createOrUpdateCustomerService->upsert($customer);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Data\Customer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Newsletter\Model\Subscriber;
use Nextouch\Wins\Service\Customer\CreateOrUpdateCustomer as CreateOrUpdateCustomerService;

class UpdateCustomer implements ObserverInterface
{
    private CustomerRepositoryInterface $customerRepository;
    private CreateOrUpdateCustomerService $createOrUpdateCustomerService;
    private Subscriber $subscriber;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CreateOrUpdateCustomerService $createOrUpdateCustomerService,
        Subscriber $subscriber
    ) {
        $this->customerRepository = $customerRepository;
        $this->createOrUpdateCustomerService = $createOrUpdateCustomerService;
        $this->subscriber = $subscriber;
    }

    public function execute(Observer $observer): void
    {
        $email = $observer->getData('email');

        /** @var Customer $customer */
        $customer = $this->customerRepository->get($email);
        $checkSubscriber = $this->subscriber->loadBySubscriberEmail($email, (int) $customer->getWebsiteId());
        $customer->getExtensionAttributes()->setIsSubscribed($checkSubscriber->isSubscribed());

        $this->createOrUpdateCustomerService->upsert($customer);
    }
}

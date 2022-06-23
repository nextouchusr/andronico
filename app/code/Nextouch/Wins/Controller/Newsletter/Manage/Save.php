<?php
declare(strict_types=1);

namespace Nextouch\Wins\Controller\Newsletter\Manage;

use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Model\Data\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Nextouch\Wins\Service\Customer\CreateOrUpdateCustomer as CreateOrUpdateCustomerService;

class Save extends \Magento\Newsletter\Controller\Manage\Save
{
    private CreateOrUpdateCustomerService $createOrUpdateCustomerService;

    public function __construct(
        Context $context,
        Session $customerSession,
        Validator $formKeyValidator,
        StoreManagerInterface $storeManager,
        CustomerRepository $customerRepository,
        SubscriptionManagerInterface $subscriptionManager,
        CreateOrUpdateCustomerService $createOrUpdateCustomerService
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $formKeyValidator,
            $storeManager,
            $customerRepository,
            $subscriptionManager
        );
        $this->createOrUpdateCustomerService = $createOrUpdateCustomerService;
    }

    public function execute()
    {
        $result = parent::execute();

        $customerId = $this->_customerSession->getCustomerId();

        /** @var Customer $customer */
        $customer = $this->customerRepository->getById($customerId);

        $this->createOrUpdateCustomerService->upsert($customer);

        return $result;
    }
}

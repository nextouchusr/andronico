<?php
declare(strict_types=1);

namespace Nextouch\Wins\Cron;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Data\Customer;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Customer\Api\Data\CustomerInterface;
use Nextouch\Wins\Service\Customer\CreateOrUpdateCustomer as CreateOrUpdateCustomerService;
use function Lambdish\Phunctional\each;

class RetryCustomerUpsert
{
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private CustomerRepositoryInterface $customerRepository;
    private CreateOrUpdateCustomerService $createOrUpdateCustomerService;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CustomerRepositoryInterface $customerRepository,
        CreateOrUpdateCustomerService $createOrUpdateCustomerService
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerRepository = $customerRepository;
        $this->createOrUpdateCustomerService = $createOrUpdateCustomerService;
    }

    /**
     * @throws LocalizedException
     */
    public function execute(): void
    {
        $criteria = $this->searchCriteriaBuilder
            ->addFilter(CustomerInterface::CUSTOMER_SYNC_FAILURES, 0, 'gt')
            ->create();

        $customers = $this->customerRepository->getList($criteria)->getItems();

        each(fn(Customer $item) => $this->createOrUpdateCustomerService->upsert($item), $customers);
    }
}

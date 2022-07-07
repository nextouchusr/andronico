<?php
declare(strict_types=1);

namespace Nextouch\Wins\Controller\Customer\Address;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Customer\Model\Data\Customer;
use Magento\Customer\Model\Metadata\FormFactory;
use Magento\Customer\Model\Session;
use Magento\Directory\Helper\Data as HelperData;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Filesystem;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\View\Result\PageFactory;
use Nextouch\Wins\Service\Customer\CreateOrUpdateCustomer as CreateOrUpdateCustomerService;

class FormPost extends \Magento\Customer\Controller\Address\FormPost
{
    private CustomerRepositoryInterface $customerRepository;
    private CreateOrUpdateCustomerService $createOrUpdateCustomerService;

    public function __construct(
        Context $context,
        Session $customerSession,
        FormKeyValidator $formKeyValidator,
        FormFactory $formFactory,
        AddressRepositoryInterface $addressRepository,
        AddressInterfaceFactory $addressDataFactory,
        RegionInterfaceFactory $regionDataFactory,
        DataObjectProcessor $dataProcessor,
        DataObjectHelper $dataObjectHelper,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        RegionFactory $regionFactory,
        HelperData $helperData,
        CustomerRepositoryInterface $customerRepository,
        CreateOrUpdateCustomerService $createOrUpdateCustomerService,
        Filesystem $filesystem = null
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $formKeyValidator,
            $formFactory,
            $addressRepository,
            $addressDataFactory,
            $regionDataFactory,
            $dataProcessor,
            $dataObjectHelper,
            $resultForwardFactory,
            $resultPageFactory,
            $regionFactory,
            $helperData,
            $filesystem
        );
        $this->customerRepository = $customerRepository;
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

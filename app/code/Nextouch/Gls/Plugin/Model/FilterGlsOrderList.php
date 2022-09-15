<?php
declare(strict_types=1);

namespace Nextouch\Gls\Plugin\Model;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\User\Api\Data\UserInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;
use Nextouch\Gls\Model\Carrier\Gls;
use Nextouch\Sales\Model\Order\Status;

class FilterGlsOrderList
{
    private UserContextInterface $userContext;
    private CollectionFactory $collectionFactory;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        UserContextInterface $userContext,
        CollectionFactory $collectionFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->userContext = $userContext;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeGetList(OrderRepositoryInterface $subject, SearchCriteriaInterface $searchCriteria): array
    {
        if ($this->canFilter()) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('shipping_method', Gls::SHIPPING_METHOD)
                ->addFilter('status', Status::PAID['status'])
                ->addFilter('state', Status::PAID['state'])
                ->create();
        }

        return [$searchCriteria];
    }

    private function canFilter(): bool
    {
        return (
            $this->userContext->getUserType() === UserContextInterface::USER_TYPE_ADMIN &&
            $this->getUser()->getUserName() === Gls::ADMIN_USERNAME
        );
    }

    private function getUser(): UserInterface
    {
        $userId = $this->userContext->getUserId();

        /** @var UserInterface $user */
        $user = $this->collectionFactory->create()->getItemById($userId);

        return $user;
    }
}

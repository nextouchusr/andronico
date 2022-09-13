<?php
declare(strict_types=1);

namespace Nextouch\Gls\Plugin\Model;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\User\Api\Data\UserInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;
use Nextouch\Gls\Model\Carrier\Gls;
use function Lambdish\Phunctional\first;

class UpdateGlsOrderStatus
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
     * @throws NoSuchEntityException
     */
    public function beforeSave(OrderRepositoryInterface $subject, OrderInterface $entity): array
    {
        if ($this->canUpdate()) {
            $order = $this->updateFields($subject, $entity);

            return [$order];
        }

        return [$entity];
    }

    private function canUpdate(): bool
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

    /**
     * @throws NoSuchEntityException
     */
    private function updateFields(OrderRepositoryInterface $orderRepository, OrderInterface $entity): OrderInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(OrderInterface::INCREMENT_ID, $entity->getIncrementId())
            ->create();

        /** @var OrderInterface $order */
        $order = first($orderRepository->getList($searchCriteria)->getItems());

        if (!$order) {
            throw new NoSuchEntityException(__('The order that was requested does not exist.'));
        }

        $order->setStatus($entity->getStatus());
        $order->setState($entity->getState());

        return $order;
    }
}

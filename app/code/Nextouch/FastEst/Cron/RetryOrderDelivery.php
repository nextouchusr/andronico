<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Cron;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\FastEst\Service\CreateNewDelivery as CreateNewDeliveryService;
use Nextouch\Sales\Api\Data\OrderInterface;
use function Lambdish\Phunctional\each;

class RetryOrderDelivery
{
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private OrderRepositoryInterface $orderRepository;
    private CreateNewDeliveryService $createNewDeliveryService;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        CreateNewDeliveryService $createNewDeliveryService
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->createNewDeliveryService = $createNewDeliveryService;
    }

    public function execute(): void
    {
        $criteria = $this->searchCriteriaBuilder
            ->addFilter(OrderInterface::SHIPPING_SYNC_FAILURES, 0, 'gt')
            ->addFilter(OrderInterface::IS_PARKED, false)
            ->create();

        $orders = $this->orderRepository->getList($criteria)->getItems();

        each(fn(Order $item) => $this->createNewDeliveryService->create($item), $orders);
    }
}

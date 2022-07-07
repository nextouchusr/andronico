<?php
declare(strict_types=1);

namespace Nextouch\Wins\Cron;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Wins\Service\Order\CreateNewOrder as CreateNewOrderService;
use function Lambdish\Phunctional\each;

class RetryOrderCreation
{
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private OrderRepositoryInterface $orderRepository;
    private CreateNewOrderService $createNewOrderService;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        CreateNewOrderService $createNewOrderService
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->createNewOrderService = $createNewOrderService;
    }

    public function execute(): void
    {
        $criteria = $this->searchCriteriaBuilder
            ->addFilter(OrderInterface::ORDER_SYNC_FAILURES, 0, 'gt')
            ->create();

        $orders = $this->orderRepository->getList($criteria)->getItems();

        each(fn(Order $item) => $this->createNewOrderService->create($item), $orders);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Cron;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\FastEst\Service\ConfirmParkedDelivery as ConfirmParkedDeliveryService;
use Nextouch\Sales\Api\Data\OrderInterface;
use function Lambdish\Phunctional\each;

class RetryConfirmParkedDelivery
{
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private OrderRepositoryInterface $orderRepository;
    private ConfirmParkedDeliveryService $confirmParkedDeliveryService;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        ConfirmParkedDeliveryService $confirmParkedDeliveryService
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->confirmParkedDeliveryService = $confirmParkedDeliveryService;
    }

    public function execute(): void
    {
        $criteria = $this->searchCriteriaBuilder
            ->addFilter(OrderInterface::SHIPPING_SYNC_FAILURES, 0, 'gt')
            ->addFilter(OrderInterface::IS_PARKED, true)
            ->create();

        $orders = $this->orderRepository->getList($criteria)->getItems();

        each(fn(Order $item) => $this->confirmParkedDeliveryService->confirm($item), $orders);
    }
}

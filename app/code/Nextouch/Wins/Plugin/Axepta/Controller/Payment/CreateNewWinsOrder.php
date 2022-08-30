<?php
declare(strict_types=1);

namespace Nextouch\Wins\Plugin\Axepta\Controller\Payment;

use Magento\Checkout\Model\Type\Onepage as OnepageCheckout;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\Wins\Service\Order\CreateNewOrder as CreateNewOrderService;

class CreateNewWinsOrder
{
    private OnepageCheckout $onepageCheckout;
    private OrderRepositoryInterface $orderRepository;
    private CreateNewOrderService $createNewOrderService;

    public function __construct(
        OnepageCheckout $onepageCheckout,
        OrderRepositoryInterface $orderRepository,
        CreateNewOrderService $createNewOrderService
    ) {
        $this->onepageCheckout = $onepageCheckout;
        $this->orderRepository = $orderRepository;
        $this->createNewOrderService = $createNewOrderService;
    }

    public function afterExecute(): void
    {
        $orderId = $this->getLastOrderId();

        /** @var Order $order */
        $order = $this->orderRepository->get($orderId);

        $this->createNewOrderService->create($order);
    }

    private function getLastOrderId()
    {
        return $this->onepageCheckout->getCheckout()->getLastOrderId();
    }
}

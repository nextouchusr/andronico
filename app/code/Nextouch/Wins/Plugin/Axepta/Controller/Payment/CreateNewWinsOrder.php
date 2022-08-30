<?php
declare(strict_types=1);

namespace Nextouch\Wins\Plugin\Axepta\Controller\Payment;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\Wins\Service\Order\CreateNewOrder as CreateNewOrderService;

class CreateNewWinsOrder
{
    private CheckoutSession $checkoutSession;
    private OrderRepositoryInterface $orderRepository;
    private CreateNewOrderService $createNewOrderService;

    public function __construct(
        CheckoutSession $checkoutSession,
        OrderRepositoryInterface $orderRepository,
        CreateNewOrderService $createNewOrderService
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->orderRepository = $orderRepository;
        $this->createNewOrderService = $createNewOrderService;
    }

    public function afterExecute(): void
    {
        $orderId = $this->checkoutSession->getLastOrderId();

        /** @var Order $order */
        $order = $this->orderRepository->get($orderId);

        $this->createNewOrderService->create($order);
    }
}

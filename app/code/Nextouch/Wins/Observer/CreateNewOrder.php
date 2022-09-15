<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\Wins\Service\Order\CreateNewOrder as CreateNewOrderService;
use function Lambdish\Phunctional\first;

class CreateNewOrder implements ObserverInterface
{
    private OrderRepositoryInterface $orderRepository;
    private CreateNewOrderService $createNewOrderService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CreateNewOrderService $createNewOrderService
    ) {
        $this->orderRepository = $orderRepository;
        $this->createNewOrderService = $createNewOrderService;
    }

    public function execute(Observer $observer): void
    {
        $orderId = first($observer->getData('order_ids'));

        /** @var Order $order */
        $order = $this->orderRepository->get($orderId);
        $hasCreditCardPayment = $order->getPayment()->getMethod() === 'axepta_paymentservice';

        if ($hasCreditCardPayment) {
            return;
        }

        $this->createNewOrderService->create($order);
    }
}

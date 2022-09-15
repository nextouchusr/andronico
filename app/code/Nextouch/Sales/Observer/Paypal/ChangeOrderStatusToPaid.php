<?php
declare(strict_types=1);

namespace Nextouch\Sales\Observer\Paypal;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order\Status;

class ChangeOrderStatusToPaid implements ObserverInterface
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function execute(Observer $observer): void
    {
        /** @var OrderInterface $order */
        $order = $observer->getData('order');

        if ($this->canChangeStatus($order)) {
            $order->setStatus(Status::PAID['status']);
            $order->setState(Status::PAID['state']);

            $this->orderRepository->save($order);
        }
    }

    private function canChangeStatus(OrderInterface $order): bool
    {
        return $order->getStatus() === Status::PROCESSING['status'];
    }
}

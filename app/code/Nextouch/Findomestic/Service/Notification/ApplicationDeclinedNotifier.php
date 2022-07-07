<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Notification;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderManagementInterface;
use Nextouch\Findomestic\Model\Request\Installment\Notification as NotificationRequest;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class ApplicationDeclinedNotifier implements InstallmentNotifierInterface
{
    private OrderRepositoryInterface $orderRepository;
    private OrderManagementInterface $orderManagement;
    private ManagerInterface $eventManager;
    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderManagementInterface $orderManagement,
        ManagerInterface $eventManager,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderManagement = $orderManagement;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
    }

    public function execute(NotificationRequest $request): void
    {
        try {
            $this->eventManager->dispatch('findomestic_application_declined_start', [
                'request' => $request,
            ]);

            $order = $this->cancelOrder($request);

            $this->eventManager->dispatch('findomestic_application_declined_end', [
                'request' => $request,
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws LocalizedException
     */
    private function cancelOrder(NotificationRequest $request): Order
    {
        $orderNumber = $request->getOrderNumber();

        /** @var Order $order */
        $order = $this->orderRepository->getByIncrementId($orderNumber);
        $order->setFindomesticApplicationDecline();
        $this->orderRepository->save($order);

        $this->orderManagement->cancel((int) $order->getEntityId());

        return $order;
    }
}

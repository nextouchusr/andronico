<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\ShipmentInterface;
use Nextouch\Findomestic\Service\Installment\ActivateInstallment as ActivateInstallmentService;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class ActivateOrderInstallment implements ObserverInterface
{
    private OrderRepositoryInterface $orderRepository;
    private ActivateInstallmentService $activateInstallmentService;
    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ActivateInstallmentService $activateInstallmentService,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->activateInstallmentService = $activateInstallmentService;
        $this->logger = $logger;
    }

    /**
     * @noinspection PhpCastIsUnnecessaryInspection
     */
    public function execute(Observer $observer): void
    {
        try {
            /** @var ShipmentInterface $shipment */
            $shipment = $observer->getData('shipment');
            $orderId = (int) $shipment->getOrderId();

            /** @var Order $order */
            $order = $this->orderRepository->get($orderId);

            $this->activateInstallmentService->activate($order);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}

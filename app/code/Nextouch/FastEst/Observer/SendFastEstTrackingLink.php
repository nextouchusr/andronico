<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Shipment;
use Nextouch\FastEst\Helper\FastEstConfig;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Wins\Service\Order\SendCurrentOrderStatus;

class SendFastEstTrackingLink implements ObserverInterface
{
    private OrderRepositoryInterface $orderRepository;
    private SendCurrentOrderStatus $sendCurrentOrderStatus;
    private FastEstConfig $config;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SendCurrentOrderStatus $sendCurrentOrderStatus,
        FastEstConfig $config
    ) {
        $this->orderRepository = $orderRepository;
        $this->sendCurrentOrderStatus = $sendCurrentOrderStatus;
        $this->config = $config;
    }

    public function execute(Observer $observer): void
    {
        /** @var Shipment $shipment */
        $shipment = $observer->getData('shipment');
        $order = $shipment->getOrder();
        $fastEstOrder = $this->orderRepository->get((int) $order->getEntityId());

        if (!$fastEstOrder->isShippedByFastEst()) {
            return;
        }

        $trackingLink = $this->config->getTrackingLink($order->getIncrementId());

        $this->sendCurrentOrderStatus->execute($order, null, $trackingLink);
    }
}

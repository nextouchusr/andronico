<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Plugin\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\ShipmentItemCreationInterfaceFactory;
use Magento\Sales\Api\ShipOrderInterface;
use Nextouch\FastEst\Helper\FastEstConfig;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use Nextouch\Sales\Api\OrderManagementInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Wins\Service\Order\SendCurrentOrderStatus;
use function Lambdish\Phunctional\map;

class SendFastEstTrackingLink
{
    private OrderRepositoryInterface $orderRepository;
    private ShipmentItemCreationInterfaceFactory $shipmentItemCreationFactory;
    private ShipOrderInterface $shipOrder;
    private SendCurrentOrderStatus $sendCurrentOrderStatus;
    private FastEstConfig $config;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ShipmentItemCreationInterfaceFactory $shipmentItemCreationFactory,
        ShipOrderInterface $shipOrder,
        SendCurrentOrderStatus $sendCurrentOrderStatus,
        FastEstConfig $config
    ) {
        $this->orderRepository = $orderRepository;
        $this->shipmentItemCreationFactory = $shipmentItemCreationFactory;
        $this->shipOrder = $shipOrder;
        $this->sendCurrentOrderStatus = $sendCurrentOrderStatus;
        $this->config = $config;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterInCharge(OrderManagementInterface $subject, int $result): int
    {
        $order = $this->orderRepository->get($result);

        if ($order->isShippedByFastEst() && $order->canShip()) {
            $this->shipOrder($order);
            $this->sendTrackingLink($order);
        }

        return $result;
    }

    private function shipOrder(OrderInterface $order): void
    {
        $shipmentItems = map(function (OrderItemInterface $orderItem) {
            $shipmentItem = $this->shipmentItemCreationFactory->create();
            $shipmentItem->setOrderItemId($orderItem->getItemId());
            $shipmentItem->setQty($orderItem->getQtyToShip());

            return $shipmentItem;
        }, $order->getItems());

        $this->shipOrder->execute($order->getEntityId(), $shipmentItems);
    }

    private function sendTrackingLink(OrderInterface $order): void
    {
        $trackingLink = $this->config->getTrackingLink($order->getIncrementId());

        $this->sendCurrentOrderStatus->execute($order, null, $trackingLink);
    }
}

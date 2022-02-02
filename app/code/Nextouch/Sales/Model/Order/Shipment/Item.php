<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order\Shipment;

use Magento\Framework\App\ObjectManager;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use Nextouch\Sales\Api\Data\ShipmentInterface;
use Nextouch\Sales\Api\Data\ShipmentItemInterface;
use Nextouch\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;
use Nextouch\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;

class Item extends \Magento\Sales\Model\Order\Shipment\Item implements ShipmentItemInterface
{
    public function getShipment(): ?ShipmentInterface
    {
        if ($this->getData(self::SHIPMENT)) {
            return $this->getData(self::SHIPMENT);
        }

        $collectionFactory = ObjectManager::getInstance()->get(ShipmentCollectionFactory::class);
        $shipmentId = $this->_shipment ? $this->_shipment->getId() : null;
        $shipment = $collectionFactory->create()->getItemById((int) $shipmentId);
        $this->setData(self::SHIPMENT, $shipment);

        return $shipment;
    }

    public function getOrderItem(): ?OrderItemInterface
    {
        if ($this->getData(self::ORDER_ITEM)) {
            return $this->getData(self::ORDER_ITEM);
        }

        if ($this->getShipment()) {
            $orderItem = $this->getShipment()->getOrder()->getItemById($this->getOrderItemId());
            $this->setData(self::ORDER_ITEM, $orderItem);

            return $orderItem;
        }

        $collectionFactory = ObjectManager::getInstance()->get(OrderItemCollectionFactory::class);
        $orderItem = $collectionFactory->create()->getItemById($this->getOrderItemId());
        $this->setData(self::ORDER_ITEM, $orderItem);

        return $orderItem;
    }
}

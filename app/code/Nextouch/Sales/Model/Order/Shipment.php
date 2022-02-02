<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order;

use Magento\Framework\App\ObjectManager;
use Nextouch\Sales\Api\Data\OrderAddressInterface;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\Data\ShipmentInterface;
use Nextouch\Sales\Model\Order\Shipment\Item;
use Nextouch\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Nextouch\Sales\Model\ResourceModel\Order\Shipment\Item\CollectionFactory as ItemCollectionFactory;
use function Lambdish\Phunctional\each;

class Shipment extends \Magento\Sales\Model\Order\Shipment implements ShipmentInterface
{
    public function getOrder(): OrderInterface
    {
        if ($this->getData(self::ORDER)) {
            return $this->getData(self::ORDER);
        }

        $collectionFactory = ObjectManager::getInstance()->get(OrderCollectionFactory::class);
        $order = $collectionFactory->create()->getItemById($this->getOrderId());
        $this->setData(self::ORDER, $order);

        return $order;
    }

    public function getShippingAddress(): OrderAddressInterface
    {
        return $this->getOrder()->getShippingAddress();
    }

    public function getItems(): array
    {
        if (!$this->getData(self::ITEMS)) {
            $collectionFactory = ObjectManager::getInstance()->get(ItemCollectionFactory::class);
            $collection = $collectionFactory->create()->setShipmentFilter($this->getId());

            if ($this->getId()) {
                each(fn(Item $item) => $item->setShipment($this), $collection);
                $this->setData(self::ITEMS, $collection->getItems());
            }
        }

        $items = $this->getData(self::ITEMS);
        if ($items !== null && !is_array($items)) {
            $items = $items->getItems();
        }

        return $items ?? [];
    }
}

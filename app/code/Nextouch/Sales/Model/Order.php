<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model;

use Amasty\Deliverydate\Api\Data\DeliverydateInterface;
use Amasty\Deliverydate\Model\ResourceModel\Deliverydate\CollectionFactory as DeliveryInformationCollectionFactory;
use Magento\Framework\App\ObjectManager;
use Nextouch\Sales\Api\Data\OrderAddressInterface;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use Nextouch\Sales\Model\ResourceModel\Order\Address\CollectionFactory as AddressCollectionFactory;
use Nextouch\Sales\Model\ResourceModel\Order\Item\CollectionFactory as ItemCollectionFactory;

class Order extends \Magento\Sales\Model\Order implements OrderInterface
{
    public function isShippedBy(string $shippingMethod): bool
    {
        return $this->getShippingMethod() === $shippingMethod;
    }

    public function getShippingAddress(): ?OrderAddressInterface
    {
        $address = parent::getShippingAddress();
        $addressId = $address ? $address->getId() : null;

        $collectionFactory = ObjectManager::getInstance()->get(AddressCollectionFactory::class);

        return $collectionFactory->create()->getItemById((int) $addressId);
    }

    public function getBillingAddress(): ?OrderAddressInterface
    {
        $address = parent::getBillingAddress();
        $addressId = $address ? $address->getId() : null;

        $collectionFactory = ObjectManager::getInstance()->get(AddressCollectionFactory::class);

        return $collectionFactory->create()->getItemById((int) $addressId);
    }

    public function getItems(): array
    {
        if (!$this->getData(self::ITEMS)) {
            $collectionFactory = ObjectManager::getInstance()->get(ItemCollectionFactory::class);
            $items = $collectionFactory
                ->create()
                ->addFilter(OrderItemInterface::ORDER_ID, $this->getId())
                ->getItems();

            $this->setData(self::ITEMS, $items);
        }

        return $this->getData(self::ITEMS);
    }

    public function getItemById($itemId): ?OrderItemInterface
    {
        $items = $this->getItems();

        return $items[$itemId] ?? null;
    }

    public function getDeliveryInformation(): DeliverydateInterface
    {
        if (!$this->getData(self::DELIVERY_INFORMATION)) {
            $collectionFactory = ObjectManager::getInstance()->get(DeliveryInformationCollectionFactory::class);
            $deliveryInformation = $collectionFactory
                ->create()
                ->addFilter(DeliverydateInterface::ORDER_ID, $this->getId())
                ->getFirstItem();

            $this->setData(self::DELIVERY_INFORMATION, $deliveryInformation);
        }

        return $this->getData(self::DELIVERY_INFORMATION);
    }

    public function getDeliveryDate(): string
    {
        return (string) $this->getDeliveryInformation()->getDate();
    }

    public function getDeliveryTime(): string
    {
        return (string) $this->getDeliveryInformation()->getTime();
    }

    public function getDeliveryComment(): string
    {
        return (string) $this->getDeliveryInformation()->getComment();
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Nextouch\Sales\Api\Data\OrderAddressInterface;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use Nextouch\Sales\Model\ResourceModel\Order\Address\CollectionFactory as AddressCollectionFactory;
use Nextouch\Sales\Model\ResourceModel\Order\Item\CollectionFactory as ItemCollectionFactory;

class Order extends \Magento\Sales\Model\Order implements OrderInterface
{
    public function isShippedBy(string $carrierCode): bool
    {
        $shippingMethod = $this->getShippingMethod(true);

        return $shippingMethod->getData(ShippingMethodInterface::KEY_CARRIER_CODE) === $carrierCode;
    }

    public function getShippingAddress(): ?OrderAddressInterface
    {
        $address = parent::getShippingAddress();
        $addressId = $address ? $address->getId() : null;

        $addressCollectionFactory = ObjectManager::getInstance()->get(AddressCollectionFactory::class);

        return $addressCollectionFactory->create()->getItemById((int) $addressId);
    }

    public function getItems(): array
    {
        if (!$this->getData(self::ITEMS)) {
            $itemCollectionFactory = ObjectManager::getInstance()->get(ItemCollectionFactory::class);
            $items = $itemCollectionFactory
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
}

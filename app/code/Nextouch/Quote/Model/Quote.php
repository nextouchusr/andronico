<?php
declare(strict_types=1);

namespace Nextouch\Quote\Model;

use Magento\Framework\App\ObjectManager;
use Nextouch\Dhl\Model\Carrier\Dhl;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Gls\Model\Carrier\Gls;
use Nextouch\Quote\Api\Data\AddressInterface;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Quote\Api\Data\CartItemInterface;
use Nextouch\Quote\Model\ResourceModel\Quote\Address\CollectionFactory as AddressCollectionFactory;
use Nextouch\Quote\Model\ResourceModel\Quote\Item\CollectionFactory as ItemCollectionFactory;
use function Lambdish\Phunctional\all;
use function Lambdish\Phunctional\some;

class Quote extends \Magento\Quote\Model\Quote implements CartInterface
{
    public function getShippingAddress(): ?AddressInterface
    {
        $address = parent::getShippingAddress();
        $addressId = $address ? $address->getId() : null;

        $collectionFactory = ObjectManager::getInstance()->get(AddressCollectionFactory::class);

        return $collectionFactory->create()->getItemById((int) $addressId);
    }

    public function getBillingAddress(): ?AddressInterface
    {
        $address = parent::getBillingAddress();
        $addressId = $address ? $address->getId() : null;

        $collectionFactory = ObjectManager::getInstance()->get(AddressCollectionFactory::class);

        return $collectionFactory->create()->getItemById((int) $addressId);
    }

    public function getFindomesticApplicationId(): string
    {
        return (string) $this->getData(self::FINDOMESTIC_APPLICATION_ID);
    }

    public function setFindomesticApplicationId(string $applicationId): CartInterface
    {
        $this->setData(self::FINDOMESTIC_APPLICATION_ID, $applicationId);

        return $this;
    }

    public function getFindomesticIssuerInstallmentId(): string
    {
        return (string) $this->getData(self::FINDOMESTIC_ISSUER_INSTALLMENT_ID);
    }

    public function setFindomesticIssuerInstallmentId(string $issuerInstallmentId): CartInterface
    {
        $this->setData(self::FINDOMESTIC_ISSUER_INSTALLMENT_ID, $issuerInstallmentId);

        return $this;
    }

    public function getItems(): array
    {
        if (!$this->getData(self::KEY_ITEMS)) {
            $collectionFactory = ObjectManager::getInstance()->get(ItemCollectionFactory::class);
            $items = $collectionFactory
                ->create()
                ->addFilter(CartItemInterface::KEY_QUOTE_ID, $this->getId())
                ->getItems();

            $this->setData(self::KEY_ITEMS, $items);
        }

        return $this->getData(self::KEY_ITEMS);
    }

    public function isShippableWithInStorePickup(): bool
    {
        return all(function (CartItemInterface $item) {
            return $item->getProduct()->isPickupable();
        }, $this->getItems());
    }

    public function isShippableWithFastEst(): bool
    {
        return some(function (CartItemInterface $item) {
            $selectableCarrier = $item->getProduct()->getSelectableCarrier();

            return $selectableCarrier === FastEst::CODE;
        }, $this->getItems());
    }

    public function isShippableWithDhl(): bool
    {
        $isShippableWithDhl = some(function (CartItemInterface $item) {
            $selectableCarrier = $item->getProduct()->getSelectableCarrier();

            return $selectableCarrier === Dhl::CODE;
        }, $this->getItems());

        return $isShippableWithDhl && !$this->isShippableWithFastEst();
    }

    public function isShippableWithGls(): bool
    {
        return all(function (CartItemInterface $item) {
            $selectableCarrier = $item->getProduct()->getSelectableCarrier();

            return $selectableCarrier === Gls::CODE;
        }, $this->getItems());
    }
}

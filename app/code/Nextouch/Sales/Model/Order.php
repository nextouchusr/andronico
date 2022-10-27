<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model;

use Amasty\Deliverydate\Api\Data\DeliverydateInterface;
use Amasty\Deliverydate\Model\ResourceModel\Deliverydate\CollectionFactory as DeliveryInformationCollectionFactory;
use Magento\Framework\App\ObjectManager;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Gls\Model\Carrier\Gls;
use Nextouch\Sales\Api\Data\OrderAddressInterface;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use Nextouch\Sales\Model\ResourceModel\Order\Address\CollectionFactory as AddressCollectionFactory;
use Nextouch\Sales\Model\ResourceModel\Order\Item\CollectionFactory as ItemCollectionFactory;

class Order extends \Magento\Sales\Model\Order implements OrderInterface
{
    public function isShippedByFastEst(): bool
    {
        return $this->isShippedBy(FastEst::SHIPPING_METHOD);
    }

    public function isShippedByGls(): bool
    {
        return $this->isShippedBy(Gls::SHIPPING_METHOD);
    }

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

    public function getCompletedAt(): ?string
    {
        return $this->getData(self::COMPLETED_AT);
    }

    public function setCompletedAt(string $completedAt): self
    {
        $this->setData(self::COMPLETED_AT, $completedAt);

        return $this;
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

    public function getOrderSyncFailures(): int
    {
        return (int) $this->getData(self::ORDER_SYNC_FAILURES);
    }

    public function increaseOrderSyncFailures(): self
    {
        $failures = $this->getOrderSyncFailures();
        $this->setData(self::ORDER_SYNC_FAILURES, ++$failures);

        return $this;
    }

    public function decreaseOrderSyncFailures(): self
    {
        $failures = $this->getOrderSyncFailures();
        $this->setData(self::ORDER_SYNC_FAILURES, --$failures);

        return $this;
    }

    public function resetOrderSyncFailures(): self
    {
        $this->setData(self::ORDER_SYNC_FAILURES, 0);

        return $this;
    }

    public function getShippingSyncFailures(): int
    {
        return (int) $this->getData(self::SHIPPING_SYNC_FAILURES);
    }

    public function increaseShippingSyncFailures(): self
    {
        $failures = $this->getShippingSyncFailures();
        $this->setData(self::SHIPPING_SYNC_FAILURES, ++$failures);

        return $this;
    }

    public function decreaseShippingSyncFailures(): self
    {
        $failures = $this->getShippingSyncFailures();
        $this->setData(self::SHIPPING_SYNC_FAILURES, --$failures);

        return $this;
    }

    public function resetShippingSyncFailures(): self
    {
        $this->setData(self::SHIPPING_SYNC_FAILURES, 0);

        return $this;
    }

    public function getIsParked(): bool
    {
        return (bool) $this->getData(self::IS_PARKED);
    }

    public function setIsParked(bool $isParked): self
    {
        $this->setData(self::IS_PARKED, $isParked);

        return $this;
    }

    public function isPaid(): bool
    {
        return $this->getBaseTotalDue() <= 0;
    }

    public function getFindomesticApplicationId(): string
    {
        return (string) $this->getData(self::FINDOMESTIC_APPLICATION_ID);
    }

    public function setFindomesticApplicationId(string $applicationId): OrderInterface
    {
        $this->setData(self::FINDOMESTIC_APPLICATION_ID, $applicationId);

        return $this;
    }

    public function getFindomesticIssuerInstallmentId(): string
    {
        return (string) $this->getData(self::FINDOMESTIC_ISSUER_INSTALLMENT_ID);
    }

    public function setFindomesticIssuerInstallmentId(string $issuerInstallmentId): OrderInterface
    {
        $this->setData(self::FINDOMESTIC_ISSUER_INSTALLMENT_ID, $issuerInstallmentId);

        return $this;
    }

    public function isFindomesticApplicationInactive(): bool
    {
        $status = (int) $this->getData(self::FINDOMESTIC_APPLICATION_STATUS);

        return $status === self::FINDOMESTIC_APPLICATION_STATUS_INACTIVE;
    }

    public function setFindomesticApplicationInactive(): OrderInterface
    {
        $this->setData(self::FINDOMESTIC_APPLICATION_STATUS, self::FINDOMESTIC_APPLICATION_STATUS_INACTIVE);

        return $this;
    }

    public function isFindomesticApplicationToActivate(): bool
    {
        $status = (int) $this->getData(self::FINDOMESTIC_APPLICATION_STATUS);

        return $status === self::FINDOMESTIC_APPLICATION_STATUS_TO_ACTIVATE;
    }

    public function setFindomesticApplicationToActivate(): OrderInterface
    {
        $this->setData(self::FINDOMESTIC_APPLICATION_STATUS, self::FINDOMESTIC_APPLICATION_STATUS_TO_ACTIVATE);

        return $this;
    }

    public function isFindomesticApplicationActive(): bool
    {
        $status = (int) $this->getData(self::FINDOMESTIC_APPLICATION_STATUS);

        return $status === self::FINDOMESTIC_APPLICATION_STATUS_ACTIVE;
    }

    public function setFindomesticApplicationActive(): OrderInterface
    {
        $this->setData(self::FINDOMESTIC_APPLICATION_STATUS, self::FINDOMESTIC_APPLICATION_STATUS_ACTIVE);

        return $this;
    }

    public function isFindomesticApplicationCancel(): bool
    {
        $status = (int) $this->getData(self::FINDOMESTIC_APPLICATION_STATUS);

        return $status === self::FINDOMESTIC_APPLICATION_STATUS_CANCEL;
    }

    public function setFindomesticApplicationCancel(): OrderInterface
    {
        $this->setData(self::FINDOMESTIC_APPLICATION_STATUS, self::FINDOMESTIC_APPLICATION_STATUS_CANCEL);

        return $this;
    }

    public function isFindomesticApplicationDecline(): bool
    {
        $status = (int) $this->getData(self::FINDOMESTIC_APPLICATION_STATUS);

        return $status === self::FINDOMESTIC_APPLICATION_STATUS_DECLINE;
    }

    public function setFindomesticApplicationDecline(): OrderInterface
    {
        $this->setData(self::FINDOMESTIC_APPLICATION_STATUS, self::FINDOMESTIC_APPLICATION_STATUS_DECLINE);

        return $this;
    }

    public function hasFindomesticPayment(): bool
    {
        return $this->getPayment()->getMethod() === 'findomestic_paymentservice';
    }

    public function canActivateInstallment(): bool
    {
        return (
            !$this->canInvoice() &&
            !$this->canShip() &&
            $this->hasFindomesticPayment() &&
            $this->isFindomesticApplicationToActivate()
        );
    }

    public function canCancelInstallment(): bool
    {
        return (
            $this->isCanceled() &&
            $this->hasFindomesticPayment() &&
            !$this->isFindomesticApplicationCancel() &&
            !$this->isFindomesticApplicationDecline()
        );
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Quote\Model;

use Magento\Framework\App\ObjectManager;
use Nextouch\Quote\Api\Data\AddressInterface;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Quote\Model\ResourceModel\Quote\Address\CollectionFactory as AddressCollectionFactory;

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
}

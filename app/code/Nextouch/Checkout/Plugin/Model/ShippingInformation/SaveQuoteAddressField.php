<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Model\ShippingInformation;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\ShippingInformationManagementInterface;
use Magento\Quote\Api\Data\AddressExtensionInterface;
use Nextouch\Quote\Model\Quote\Address;

abstract class SaveQuoteAddressField
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagementInterface $subject,
        int $cartId,
        ShippingInformationInterface $shippingInformation
    ): array {
        $this->saveFieldToShippingAddress($shippingInformation);
        $this->saveFieldToBillingAddress($shippingInformation);

        return [$cartId, $shippingInformation];
    }

    private function saveFieldToShippingAddress(ShippingInformationInterface $shippingInformation): void
    {
        /** @var Address $shippingAddress */
        $shippingAddress = $shippingInformation->getShippingAddress();
        $extensionAttributes = $shippingAddress->getExtensionAttributes();

        if ($extensionAttributes) {
            $shippingAddress->setData($this->getKey(), $this->getValue($extensionAttributes));
        }
    }

    private function saveFieldToBillingAddress(ShippingInformationInterface $shippingInformation): void
    {
        /** @var Address $billingAddress */
        $billingAddress = $shippingInformation->getBillingAddress();
        $extensionAttributes = $billingAddress->getExtensionAttributes();

        if ($extensionAttributes) {
            $billingAddress->setData($this->getKey(), $this->getValue($extensionAttributes));
        }
    }

    abstract protected function getKey(): string;

    abstract protected function getValue(AddressExtensionInterface $extensionAttributes);
}

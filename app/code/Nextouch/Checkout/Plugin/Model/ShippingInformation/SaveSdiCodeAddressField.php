<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Model\ShippingInformation;

use Magento\Quote\Api\Data\AddressExtensionInterface;
use Nextouch\Quote\Api\Data\AddressInterface;

class SaveSdiCodeAddressField extends SaveQuoteAddressField
{
    protected function getKey(): string
    {
        return AddressInterface::SDI_CODE;
    }

    protected function getValue(AddressExtensionInterface $extensionAttributes): ?string
    {
        return $extensionAttributes->getSdiCode();
    }
}

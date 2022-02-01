<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Model\ShippingInformation;

use Magento\Quote\Api\Data\AddressExtensionInterface;
use Nextouch\Quote\Api\Data\AddressInterface;

class SavePecAddressField extends SaveQuoteAddressField
{
    protected function getKey(): string
    {
        return AddressInterface::PEC;
    }

    protected function getValue(AddressExtensionInterface $extensionAttributes): ?string
    {
        return $extensionAttributes->getPec();
    }
}

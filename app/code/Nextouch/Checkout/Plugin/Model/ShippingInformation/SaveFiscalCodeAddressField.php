<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Model\ShippingInformation;

use Magento\Quote\Api\Data\AddressExtensionInterface;
use Nextouch\Quote\Api\Data\AddressInterface;

class SaveFiscalCodeAddressField extends SaveQuoteAddressField
{
    protected function getKey(): string
    {
        return AddressInterface::FISCAL_CODE;
    }

    protected function getValue(AddressExtensionInterface $extensionAttributes): ?string
    {
        return $extensionAttributes->getFiscalCode();
    }
}

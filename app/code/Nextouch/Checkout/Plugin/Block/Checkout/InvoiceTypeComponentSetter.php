<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Block\Checkout;

use Magento\Checkout\Block\Checkout\AttributeMerger;
use Nextouch\Customer\Model\Config\Address\InvoiceTypes;

class InvoiceTypeComponentSetter
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterMerge(AttributeMerger $subject, array $result): array
    {
        $result['invoice_type']['component'] = 'Nextouch_Checkout/js/form/element/invoice-type';

        return $result;
    }
}

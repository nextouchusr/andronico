<?php
declare(strict_types=1);

namespace Nextouch\Customer\Model\Config\Address;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class InvoiceTypes extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    public const INVOICE_TYPE = 'invoice';
    public const RECEIPT_TYPE = 'receipt';

    public function getAllOptions(): array
    {
        $this->_options = [
            ['label' => __('Receipt'), 'value' => self::RECEIPT_TYPE],
            ['label' => __('Invoice'), 'value' => self::INVOICE_TYPE],
        ];

        return $this->_options;
    }
}

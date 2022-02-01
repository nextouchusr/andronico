<?php
declare(strict_types=1);

namespace Nextouch\Customer\Model\Config\Address;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class InvoiceTypes extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    public function getAllOptions(): array
    {
        $this->_options = [
            ['label' => __('Invoice'), 'value' => 'invoice'],
            ['label' => __('Receipt'), 'value' => 'receipt'],
        ];

        return $this->_options;
    }
}

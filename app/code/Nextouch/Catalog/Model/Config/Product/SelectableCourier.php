<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model\Config\Product;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class SelectableCarrier extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    public function getAllOptions(): array
    {
        $this->_options = [
            ['label' => 'Fast-Est', 'value' => 'fast_est_fast_est'],
            ['label' => 'DHL', 'value' => 'dhl_dhl'],
            ['label' => 'GLS', 'value' => 'gls_gls'],
        ];

        return $this->_options;
    }
}

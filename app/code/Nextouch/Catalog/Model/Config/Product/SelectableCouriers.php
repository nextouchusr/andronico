<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model\Config\Product;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class SelectableCouriers extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    public function getAllOptions(): array
    {
        $this->_options = [
            ['label' => 'Fast-Est', 'value' => 'fast_est'],
            ['label' => 'Free Shipping', 'value' => 'freeshipping'],
            ['label' => 'DHL', 'value' => 'dhl'],
        ];

        return $this->_options;
    }
}

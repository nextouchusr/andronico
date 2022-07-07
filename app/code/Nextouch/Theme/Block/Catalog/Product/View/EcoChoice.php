<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Catalog\Product\View;

use Magento\Catalog\Block\Product\View\Description;
use Nextouch\Catalog\Api\Data\ProductInterface;

class EcoChoice extends Description
{
    public function toHtml(): string
    {
        if ($this->hasEcoChoice()) {
            return parent::toHtml();
        }

        return '';
    }

    public function hasEcoChoice(): bool
    {
        return (bool) $this->getProduct()->getData(ProductInterface::ECO_CHOICE);
    }
}

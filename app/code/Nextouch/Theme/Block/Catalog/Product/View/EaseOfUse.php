<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Catalog\Product\View;

use Magento\Catalog\Block\Product\View\Description;
use Nextouch\Catalog\Api\Data\ProductInterface;

class EaseOfUse extends Description
{
    public function toHtml(): string
    {
        if ($this->hasEaseOfUse()) {
            return parent::toHtml();
        }

        return '';
    }

    public function hasEaseOfUse(): bool
    {
        return (bool) $this->getProduct()->getData(ProductInterface::EASE_OF_USE);
    }
}

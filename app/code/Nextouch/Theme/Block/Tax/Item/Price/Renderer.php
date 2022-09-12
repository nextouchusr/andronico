<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Tax\Item\Price;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Catalog\Pricing\Price\RegularPrice;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Renderer extends \Magento\Tax\Block\Item\Price\Renderer
{
    public function getProduct(): ProductInterface
    {
        return $this->getItem()->getProduct();
    }

    public function getPriceType($priceCode): PriceInterface
    {
        return $this->getProduct()->getPriceInfo()->getPrice($priceCode);
    }

    public function hasSpecialPrice(): bool
    {
        $displayRegularPrice = $this->getPriceType(RegularPrice::PRICE_CODE)->getAmount()->getValue();
        $displayFinalPrice = $this->getPriceType(FinalPrice::PRICE_CODE)->getAmount()->getValue();

        return $displayFinalPrice < $displayRegularPrice;
    }

    public function renderRowTotalFullPrice(): string
    {
        $price = $this->getPriceType(RegularPrice::PRICE_CODE)->getAmount()->getValue();
        $qty = $this->getItem()->getQty();

        return $this->priceCurrency->format(
            $price * $qty,
            true,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $this->getItem()->getStore()
        );
    }
}

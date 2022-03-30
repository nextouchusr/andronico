<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Checkout\Cart\Item;

use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Catalog\Pricing\Price\RegularPrice;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\Price\PriceInterface;

class Renderer extends \Magento\Checkout\Block\Cart\Item\Renderer
{
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

    public function renderAmount(string $priceType, array $arguments = []): string
    {
        try {
            $arguments = array_replace($this->getData(), $arguments);
            $priceRender = $this->getLayout()->getBlock('product.price.render.default');

            return $priceRender->render($priceType, $this->getProduct(), $arguments);
        } catch (LocalizedException $e) {
            return '';
        }
    }
}

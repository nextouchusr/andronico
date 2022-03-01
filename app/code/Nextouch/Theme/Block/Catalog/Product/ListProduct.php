<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Catalog\Product;

use Magento\Catalog\Model\Product;
use function Lambdish\Phunctional\search;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    public function getSavingPercent(Product $product): float
    {
        $price = $product->getPrice();
        $finalPrice = $product->getFinalPrice();

        if ($product->getTypeId() !== 'simple') {
            $children = $product->getTypeInstance()->getUsedProducts($product);
            $price = search(fn(Product $item) => $item->getPrice(), $children);
        }

        if ($finalPrice < $price) {
            return 100 - round(($finalPrice / $price) * 100);
        }

        return 0;
    }
}

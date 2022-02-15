<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Catalog\Product\View\AddTo;

use Magento\Wishlist\Model\Item;
use function Lambdish\Phunctional\search;
use function Lambdish\Phunctional\some;

class Wishlist extends \Magento\Wishlist\Block\Catalog\Product\View\AddTo\Wishlist
{
    public function isInWishlist(): bool
    {
        return some(
            fn(Item $item) => $item->getProductId() === $this->getProduct()->getId(),
            $this->_wishlistHelper->getWishlistItemCollection()
        );
    }

    public function getPostParams(): string
    {
        if ($this->isInWishlist()) {
            return $this->getRemoveParams();
        }

        return $this->getAddParams();
    }

    private function getAddParams(): string
    {
        $product = $this->getProduct();

        return $this->_wishlistHelper->getAddParams($product);
    }

    private function getRemoveParams(): string
    {
        $item = search(
            fn(Item $item) => $item->getProductId() === $this->getProduct()->getId(),
            $this->_wishlistHelper->getWishlistItemCollection()
        );

        return $this->_wishlistHelper->getRemoveParams($item);
    }
}

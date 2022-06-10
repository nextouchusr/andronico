<?php
declare(strict_types=1);

namespace Nextouch\Quote\Model\Quote;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Catalog\Api\Data\ProductInterface;
use Nextouch\Catalog\Api\ProductRepositoryInterface;
use Nextouch\Quote\Api\Data\CartItemInterface;

class Item extends \Magento\Quote\Model\Quote\Item implements CartItemInterface
{
    /**
     * @throws NoSuchEntityException
     */
    public function getProduct(): ProductInterface
    {
        $product = parent::getProduct();
        $productId = (int) $product->getId();

        $productRepository = ObjectManager::getInstance()->get(ProductRepositoryInterface::class);

        return $productRepository->getById($productId);
    }
}

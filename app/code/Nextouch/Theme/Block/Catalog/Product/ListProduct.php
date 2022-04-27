<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Catalog\Product;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Exception\LocalizedException;
use function Lambdish\Phunctional\search;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    private const QUERY_VAR_CATEGORY_ID = 'category_id';

    protected function _getProductCollection()
    {
        try {
            /** @var Collection $collection */
            $collection = parent::_getProductCollection();

            $rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
            $categoryId = (int) $this->_request->getParam(self::QUERY_VAR_CATEGORY_ID, $rootCategoryId);
            $category = $this->categoryRepository->get($categoryId);
            $categoryIds = explode(',', $category->getChildren(true));

            $collection->addCategoriesFilter(['in' => $categoryIds]);
            $this->setCollection($collection);
        } catch (LocalizedException $e) {
            $this->_logger->error($e->getMessage());
        }

        return $this->_productCollection;
    }

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

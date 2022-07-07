<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Catalog\Api\Data\ProductInterface;
use Nextouch\Catalog\Api\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    private ProductFactory $productFactory;
    private ProductResourceModel $productResourceModel;

    public function __construct(
        ProductFactory $productFactory,
        ProductResourceModel $productResourceModel
    ) {
        $this->productFactory = $productFactory;
        $this->productResourceModel = $productResourceModel;
    }

    public function getById(int $productId, bool $editMode = false, int $storeId = null): ProductInterface
    {
        $product = $this->productFactory->create();

        if ($editMode) {
            $product->setData('_edit_mode', true);
        }

        if ($storeId !== null) {
            $product->setData('store_id', $storeId);
        }

        $this->productResourceModel->load($product, $productId);

        if (!$product->getId()) {
            throw new NoSuchEntityException(__('The product that was requested does not exist.'));
        }

        return $product;
    }
}

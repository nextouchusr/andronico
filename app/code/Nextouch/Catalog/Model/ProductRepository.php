<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Catalog\Api\Data\ProductInterface;
use Nextouch\Catalog\Api\ProductRepositoryInterface;
use Nextouch\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ProductRepository implements ProductRepositoryInterface
{
    private ProductFactory $productFactory;
    private ProductResourceModel $productResourceModel;
    private CollectionFactory $collectionFactory;
    private \Magento\Catalog\Api\ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductFactory $productFactory,
        ProductResourceModel $productResourceModel,
        CollectionFactory $collectionFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->productFactory = $productFactory;
        $this->productResourceModel = $productResourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
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

    public function getByEcatCode(string $code): ProductInterface
    {
        /** @var ProductInterface $product */
        $product = $this->collectionFactory
            ->create()
            ->addFieldToFilter(ProductInterface::ALTERNATIVE_CODE, $code)
            ->getFirstItem();

        if (!$product->getId()) {
            throw new NoSuchEntityException(__('The product that was requested does not exist.'));
        }

        return $product;
    }

    public function save(ProductInterface $product, bool $saveOptions = false): ProductInterface
    {
        $this->productRepository->save($product, $saveOptions);

        return $this->getByEcatCode($product->getAlternativeCode());
    }
}

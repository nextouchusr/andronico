<?php

namespace Nextouch\Catalog\Plugin;

use Magento\Catalog\CustomerData\CompareProducts;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Helper\Product\Compare as CompareHelper;
use Magento\Catalog\Model\ProductRepository;

class AddImageToCompareProductsPlugin
{
    private CompareHelper $compareHelper;
    private ImageFactory $imageHelperFactory;
    private ProductRepository $productRepository;

    public function __construct(
        CompareHelper $compareHelper,
        ImageFactory $imageHelperFactory,
        ProductRepository $productRepository
    ) {
        $this->compareHelper = $compareHelper;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetSectionData(CompareProducts $subject, array $result): array
    {
        $images = [];

        foreach ($this->compareHelper->getItemCollection() as $item) {
            $imageHelper = $this->imageHelperFactory->create();

            try {
                $product = $this->productRepository->getById($item->getId());

                $images[$item->getId()] = $imageHelper
                    ->init($product, 'recently_compared_products_grid_content_widget')
                    ->getUrl();
            } catch (\Exception $e) {
                $images[$item->getId()] = $imageHelper->getDefaultPlaceholderUrl();
            }
        }

        $items = $result['items'];

        foreach ($items as &$item) {
            $item['image_src'] = $images[$item['id']];
        }

        $result['items'] = $items;

        return $result;
    }
}

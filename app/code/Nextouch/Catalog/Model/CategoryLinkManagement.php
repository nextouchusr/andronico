<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Api\Data\CategoryProductLinkInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Nextouch\Catalog\Api\CategoryLinkManagementInterface;
use Nextouch\Catalog\Api\Data\CategoryProductLinkSearchResultsInterface;
use Nextouch\Catalog\Api\Data\CategoryProductLinkSearchResultsInterfaceFactory;
use function Lambdish\Phunctional\map;

class CategoryLinkManagement implements CategoryLinkManagementInterface
{
    private const PRICE_SEPARATOR = '-';

    private CategoryRepositoryInterface $categoryRepository;
    private CategoryProductLinkInterfaceFactory $categoryProductLinkFactory;
    private CategoryProductLinkSearchResultsInterfaceFactory $searchResultsFactory;
    private ProductAttributeRepositoryInterface $attributeRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryProductLinkInterfaceFactory $categoryProductLinkFactory,
        CategoryProductLinkSearchResultsInterfaceFactory $searchResultsFactory,
        ProductAttributeRepositoryInterface $attributeRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryProductLinkFactory = $categoryProductLinkFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->attributeRepository = $attributeRepository;
    }

    public function getAssignedProducts(
        int $categoryId,
        SearchCriteriaInterface $searchCriteria
    ): CategoryProductLinkSearchResultsInterface {
        $category = $this->categoryRepository->get($categoryId);
        $products = $this->getCategoryProducts($category, $searchCriteria);

        $links = map(function (ProductInterface $product) use ($category) {
            $link = $this->categoryProductLinkFactory->create();
            $link->setSku($product->getSku())
                ->setPosition($product->getData('cat_index_position'))
                ->setCategoryId($category->getId());

            return $link;
        }, $products->getItems());

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($links);
        $searchResult->setTotalCount(count($links));

        return $searchResult;
    }

    private function getCategoryProducts(
        CategoryInterface $category,
        SearchCriteriaInterface $searchCriteria
    ): Collection {
        /** @var Collection $products */
        $products = $category->getProductCollection();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $attribute = $this->attributeRepository->get($filter->getField());

                if ($attribute->getFrontendInput() === 'price' && $filter->getConditionType() === 'eq') {
                    [$gteq, $lteq] = explode(self::PRICE_SEPARATOR, $filter->getValue());
                    $products->addFieldToFilter($filter->getField(), ['gteq' => $gteq]);
                    $products->addFieldToFilter($filter->getField(), ['lteq' => $lteq]);
                    continue;
                }

                $condition = $filter->getConditionType() ?: 'eq';
                $products->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        $products->addFieldToSelect('position');
        $products->groupByAttribute($products->getProductEntityMetadata()->getIdentifierField());

        return $products;
    }
}

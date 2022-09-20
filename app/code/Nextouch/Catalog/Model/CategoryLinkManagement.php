<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Api\Data\CategoryProductLinkInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Nextouch\Catalog\Api\CategoryLinkManagementInterface;
use Nextouch\Catalog\Api\Data\CategoryProductLinkSearchResultsInterface;
use Nextouch\Catalog\Api\Data\CategoryProductLinkSearchResultsInterfaceFactory;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;

class CategoryLinkManagement implements CategoryLinkManagementInterface
{
    private CategoryRepositoryInterface $categoryRepository;
    private CategoryProductLinkInterfaceFactory $categoryProductLinkFactory;
    private CategoryProductLinkSearchResultsInterfaceFactory $searchResultsFactory;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryProductLinkInterfaceFactory $categoryProductLinkFactory,
        CategoryProductLinkSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryProductLinkFactory = $categoryProductLinkFactory;
        $this->searchResultsFactory = $searchResultsFactory;
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

        each(function (FilterGroup $filterGroup) use ($products) {
            each(function (Filter $filter) use ($products) {
                $products->addFilter($filter->getField(), $filter->getValue(), $filter->getConditionType());
            }, $filterGroup->getFilters());
        }, $searchCriteria->getFilterGroups());

        $products->addFieldToSelect('position');
        $products->groupByAttribute($products->getProductEntityMetadata()->getIdentifierField());

        return $products;
    }
}

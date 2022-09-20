<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Nextouch\Catalog\Api\Data\CategoryProductLinkSearchResultsInterface;

/**
 * @api
 */
interface CategoryLinkManagementInterface
{
    /**
     * @param int $categoryId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Nextouch\Catalog\Api\Data\CategoryProductLinkSearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAssignedProducts(
        int $categoryId,
        SearchCriteriaInterface $searchCriteria
    ): CategoryProductLinkSearchResultsInterface;
}

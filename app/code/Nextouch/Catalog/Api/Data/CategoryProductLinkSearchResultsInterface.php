<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface CategoryProductLinkSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Magento\Catalog\Api\Data\CategoryProductLinkInterface[]
     */
    public function getItems();

    /**
     * @param \Magento\Catalog\Api\Data\CategoryProductLinkInterface[] $items
     * @return CategoryProductLinkSearchResultsInterface
     */
    public function setItems(array $items);
}

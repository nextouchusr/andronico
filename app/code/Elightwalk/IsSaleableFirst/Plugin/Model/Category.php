<?php

namespace Elightwalk\IsSaleableFirst\Plugin\Model;

use Magento\Catalog\Model\Category as CatalogCategory;

class Category
{
    /**
     * AfterGetDefaultSortBy
     *
     * @param CatalogCategory $subject
     * @param mixed $result
     * @return string
     */
    public function afterGetDefaultSortBy(CatalogCategory $subject, $result)
    {
        $sortBy = 'available_qty';
        return $sortBy;
    }
}

<?php

namespace Elightwalk\IsSaleableFirst\Plugin\Helper;

use Magento\Catalog\Helper\Product\ProductList as ProductListHelper;

class ProductList
{
    /**
     * getDefaultSortField
     *
     * @param ProductListHelper $subject
     * @param mixed $result
     * @return string
     */
    public function afterGetDefaultSortField(ProductListHelper $subject, $result)
    {
        $sortBy = 'available_qty';
        return $sortBy;
    }
}
<?php

namespace Elightwalk\Core\Plugin\Catalog\Model;

use Magento\Catalog\Model\Category as MagentoCategory;

class Category
{
    /**
     * AfterGetDefaultSortBy
     *
     * @param MagentoCategory $subject
     * @param mixed $result
     * @return mixed
     */
    public function afterGetDefaultSortBy(
        MagentoCategory $subject,
        $result
    ) {
        $sortBy = 'is_salable';
        return $sortBy;
    }
}

<?php

namespace Elightwalk\Core\Plugin\Catalog\Model;

use Magento\Catalog\Model\Config as CatalogConfig;

class Config
{
    /**
     * GfterGetAttributeUsedForSortByArray
     *
     * @param CatalogConfig $catalogConfig
     * @param array $options
     * @return array
     */
    public function afterGetAttributeUsedForSortByArray(
        CatalogConfig $catalogConfig,
        $options
    ) {
        $options['is_salable'] = __('Salable');
        return $options;
    }
}

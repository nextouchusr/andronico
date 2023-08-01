<?php

namespace Elightwalk\IsSaleableFirst\Plugin\Model;

use Magento\Catalog\Model\Config as CatalogConfig;

class Config
{
	/**
	 * AfterGetAttributeUsedForSortByArray
	 *
	 * @param CatalogConfig $catalogConfig
	 * @param array $options
	 * @return array
	 */
	public function afterGetAttributeUsedForSortByArray(
		CatalogConfig $catalogConfig,
		$options
	) {
		$options['available_qty'] = __('Availability');
		//Merge default sorting options with custom options
		//$options = array_merge($customOption, $options);
		return $options;
	}
}

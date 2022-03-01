<?php
declare(strict_types=1);

namespace Nextouch\Theme\Plugin\Block\Catalog\Product\ProductList\Toolbar;

use Magento\Catalog\Block\Product\ProductList\Toolbar;

class EditAvailableSortOptions
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetAvailableOrders(Toolbar $subject, array $result): array
    {
        unset($result['position']);

        return $result;
    }
}

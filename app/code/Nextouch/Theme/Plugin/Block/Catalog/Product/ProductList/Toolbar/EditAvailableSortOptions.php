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

        // Define the preferred order of keys
        $preferredOrder = array('available_qty', 'price');

        // Custom sorting function
        $customSort = function ($a, $b) use ($preferredOrder) {
            $aIndex = array_search($a, $preferredOrder);
            $bIndex = array_search($b, $preferredOrder);

            if ($aIndex === false && $bIndex === false) {
                return 0; // If both keys are not in the preferred order, maintain their current order
            } elseif ($aIndex === false) {
                return 1; // If only $a is not in the preferred order, move it to the end
            } elseif ($bIndex === false) {
                return -1; // If only $b is not in the preferred order, move it to the end
            } else {
                return $aIndex - $bIndex; // Compare the indices in the preferred order
            }
        };

        // Sort the keys using the custom sorting function
        uksort($result, $customSort);

        return $result;
    }
}

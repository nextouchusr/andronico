<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Block\Checkout;

use Magento\Checkout\Block\Checkout\AttributeMerger;

class StairValidation
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterMerge(AttributeMerger $subject, array $result): array
    {
        $result['stair']['value'] = $result['stair']['value'] ?? false;

        return $result;
    }
}

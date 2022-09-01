<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Block\Checkout;

use Magento\Checkout\Block\Checkout\AttributeMerger;

class LimitedTrafficZoneValidation
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterMerge(AttributeMerger $subject, array $result): array
    {
        $result['limited_traffic_zone']['value'] = $result['limited_traffic_zone']['value'] ?? false;

        return $result;
    }
}

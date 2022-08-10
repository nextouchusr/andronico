<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Block\Checkout;

use Magento\Checkout\Block\Checkout\AttributeMerger;

class FiscalCodeValidation
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterMerge(AttributeMerger $subject, array $result): array
    {
        $result['fiscal_code']['validation'] = [
            'required-entry' => true,
        ];

        return $result;
    }
}

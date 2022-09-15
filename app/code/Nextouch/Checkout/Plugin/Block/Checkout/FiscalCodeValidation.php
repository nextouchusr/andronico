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
            'pattern' => '^([A-Z]{6}[0-9LMNPQRSTUV]{2}[ABCDEHLMPRST]{1}[0-9LMNPQRSTUV]{2}[A-Z]{1}[0-9LMNPQRSTUV]{3}[A-Z]{1})$|([0-9]{11})$',
        ];

        return $result;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Block\Checkout;

use Magento\Checkout\Block\Checkout\AttributeMerger;

class PhoneValidation
{
    public function afterMerge(AttributeMerger $subject, array $result): array
    {
        $phoneRegex = '^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$';

        $result['telephone']['validation'] = [
            'pattern' => $phoneRegex,
        ];

        $result['mobile_phone']['validation'] = [
            'pattern' => $phoneRegex,
        ];

        return $result;
    }
}

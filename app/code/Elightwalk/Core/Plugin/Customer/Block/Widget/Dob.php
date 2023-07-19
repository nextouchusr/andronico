<?php

namespace Elightwalk\Core\Plugin\Customer\Block\Widget;

use Magento\Customer\Block\Widget\Dob as MagentoDob;

class Dob
{
    /**
     * AfterGetDateFormat
     *
     * @param MagentoDob $subject
     * @param string $escapedDateFormat
     * @return string
     */
    public function afterGetDateFormat(MagentoDob $subject, $escapedDateFormat)
    {
        return 'MM/dd/y';
    }
}

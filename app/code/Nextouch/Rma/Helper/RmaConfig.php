<?php
declare(strict_types=1);

namespace Nextouch\Rma\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class RmaConfig extends AbstractHelper
{
    private const XML_PATH_POLICY_RETURN_PERIOD = 'sales/magento_rma/policy_return_period';

    public function getPolicyReturnPeriod(): int
    {
        return (int) $this->scopeConfig->getValue(self::XML_PATH_POLICY_RETURN_PERIOD);
    }
}

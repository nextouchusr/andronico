<?php
declare(strict_types=1);

namespace Nextouch\Customer\Model\Data;

use Nextouch\Customer\Api\Data\CustomerInterface;

class Customer extends \Magento\Customer\Model\Data\Customer implements CustomerInterface
{
    public function getCustomerSyncFailures(): int
    {
        return (int) $this->getCustomAttribute(self::CUSTOMER_SYNC_FAILURES);
    }

    public function increaseCustomerSyncFailures(): self
    {
        $failures = $this->getCustomerSyncFailures();
        $this->setCustomAttribute(self::CUSTOMER_SYNC_FAILURES, ++$failures);

        return $this;
    }

    public function decreaseCustomerSyncFailures(): self
    {
        $failures = $this->getCustomerSyncFailures();
        $this->setCustomAttribute(self::CUSTOMER_SYNC_FAILURES, --$failures);

        return $this;
    }

    public function resetCustomerSyncFailures(): self
    {
        $this->setCustomAttribute(self::CUSTOMER_SYNC_FAILURES, 0);

        return $this;
    }
}

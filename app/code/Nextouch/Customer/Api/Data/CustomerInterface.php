<?php
declare(strict_types=1);

namespace Nextouch\Customer\Api\Data;

/**
 * @api
 */
interface CustomerInterface
{
    public const PRIVACY_POLICY_ACCEPTED = 'privacy_policy_accepted';
    public const CUSTOMER_SYNC_FAILURES = 'customer_sync_failures';

    /**
     * @return int
     */
    public function getCustomerSyncFailures(): int;

    /**
     * @return CustomerInterface
     */
    public function increaseCustomerSyncFailures(): self;

    /**
     * @return CustomerInterface
     */
    public function decreaseCustomerSyncFailures(): self;

    /**
     * @return CustomerInterface
     */
    public function resetCustomerSyncFailures(): self;
}

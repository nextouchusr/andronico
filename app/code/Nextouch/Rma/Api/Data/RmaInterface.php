<?php
declare(strict_types=1);

namespace Nextouch\Rma\Api\Data;

use Nextouch\Sales\Api\Data\OrderInterface;

/**
 * @api
 */
interface RmaInterface extends \Magento\Rma\Api\Data\RmaInterface
{
    public const RETURN_SYNC_FAILURES = 'return_sync_failures';
    public const FINDOMESTIC_REFUND_STATUS = 'findomestic_refund_status';

    public const FINDOMESTIC_REFUND_STATUS_UNSENT = 0;
    public const FINDOMESTIC_REFUND_STATUS_PENDING = 1;
    public const FINDOMESTIC_REFUND_STATUS_APPROVED = 2;
    public const FINDOMESTIC_REFUND_STATUS_DECLINED = 3;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function getOrder(): OrderInterface;

    /**
     * @return float
     */
    public function getAmountApproved(): float;

    /**
     * @return int
     */
    public function getReturnSyncFailures(): int;

    /**
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     */
    public function increaseReturnSyncFailures(): self;

    /**
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     */
    public function decreaseReturnSyncFailures(): self;

    /**
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     */
    public function resetReturnSyncFailures(): self;

    /**
     * @return bool
     */
    public function isFindomesticRefundStatusUnsent(): bool;

    /**
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     */
    public function setFindomesticRefundStatusUnsent(): self;

    /**
     * @return bool
     */
    public function isFindomesticRefundStatusPending(): bool;

    /**
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     */
    public function setFindomesticRefundStatusPending(): self;

    /**
     * @return bool
     */
    public function isFindomesticRefundStatusApproved(): bool;

    /**
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     */
    public function setFindomesticRefundStatusApproved(): self;

    /**
     * @return bool
     */
    public function isFindomesticRefundStatusDeclined(): bool;

    /**
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     */
    public function setFindomesticRefundStatusDeclined(): self;

    /**
     * @return bool
     */
    public function canRefundInstallment(): bool;
}

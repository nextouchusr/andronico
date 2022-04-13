<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Notification;

class InstallmentStatus
{
    public const CREATED = 'CREATED';
    public const APPROVED = 'APPROVED';
    public const DECLINED = 'DECLINED';
    public const REFUND_APPROVED = 'REFUND_APPROVED';
    public const REFUND_DECLINED = 'REFUND_DECLINED';
    public const CANCELLED = 'CANCELLED';
}

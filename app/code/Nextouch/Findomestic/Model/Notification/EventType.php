<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Notification;

class EventType
{
    public const APPLICATION_PREAPPROVED = 'APPLICATION_PREAPPROVED';
    public const SIGNATURE_COMPLETE = 'SIGNATURE_COMPLETE';
    public const DOC_UPLOAD_COMPLETE = 'DOC_UPLOAD_COMPLETE';
    public const APPLICATION_COMPLETE = 'APPLICATION_COMPLETE';
    public const INSTALLMENT_REFUND = 'INSTALLMENT_REFUND';
    public const INSTALLMENT_CANCEL = 'INSTALLMENT_CANCEL';
}

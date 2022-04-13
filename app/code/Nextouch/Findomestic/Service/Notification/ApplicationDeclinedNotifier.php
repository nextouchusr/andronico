<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Notification;

use Nextouch\Findomestic\Model\Request\Installment\Notification as NotificationRequest;

class ApplicationDeclinedNotifier implements InstallmentNotifierInterface
{
    public function execute(NotificationRequest $request): void
    {
        // TODO: Implement execute() method.
    }
}

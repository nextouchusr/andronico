<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Api;

use Nextouch\Findomestic\Model\Request\Installment\Notification as NotificationRequest;

/**
 * @api
 */
interface NotificationProcessorInterface
{
    /**
     * @param string $vendorId
     * @param \Nextouch\Findomestic\Model\Request\Installment\Notification $request
     * @return void
     */
    public function notify(string $vendorId, NotificationRequest $request): void;
}

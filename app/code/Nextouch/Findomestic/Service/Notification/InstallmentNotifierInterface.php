<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Notification;

use Nextouch\Findomestic\Model\Request\Installment\Notification as NotificationRequest;

/**
 * @api
 */
interface InstallmentNotifierInterface
{
    /**
     * @param NotificationRequest $request
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(NotificationRequest $request): void;
}

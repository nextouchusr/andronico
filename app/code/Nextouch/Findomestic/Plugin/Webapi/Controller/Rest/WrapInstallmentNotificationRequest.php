<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Plugin\Webapi\Controller\Rest;

use Magento\Webapi\Controller\Rest\InputParamsResolver;
use Nextouch\Findomestic\Api\NotificationProcessorInterface;

class WrapInstallmentNotificationRequest
{
    public function afterGetInputData(InputParamsResolver $subject, array $result): array
    {
        if (!$this->canWrap($subject)) {
            return $result;
        }

        return [
            'vendorId' => $result['vendorId'],
            'request' => [
                'applicationId' => $result['applicationId'],
                'orderNumber' => $result['orderNumber'] ?? null,
                'issuerInstallmentId' => $result['issuerInstallmentId'],
                'notificationEvent' => $result['notificationEvent'],
            ],
        ];
    }

    private function canWrap(InputParamsResolver $subject): bool
    {
        return $subject->getRoute()->getServiceClass() === NotificationProcessorInterface::class;
    }
}

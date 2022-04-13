<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Request\Installment;

use Nextouch\Findomestic\Api\Data\InputInterface;
use Nextouch\Findomestic\Model\Notification\ApplicationStatus;
use Nextouch\Findomestic\Model\Notification\Event as NotificationEvent;
use Nextouch\Findomestic\Model\Notification\EventType;
use Nextouch\Findomestic\Model\Notification\InstallmentStatus;

class Notification implements InputInterface
{
    private string $applicationId;
    private string $issuerInstallmentId;
    private NotificationEvent $notificationEvent;
    private ?string $orderNumber;

    public function __construct(
        string $applicationId,
        string $issuerInstallmentId,
        NotificationEvent $notificationEvent,
        ?string $orderNumber = null
    ) {
        $this->applicationId = $applicationId;
        $this->issuerInstallmentId = $issuerInstallmentId;
        $this->notificationEvent = $notificationEvent;
        $this->orderNumber = $orderNumber;
    }

    /**
     * @return string
     */
    public function getApplicationId(): string
    {
        return $this->applicationId;
    }

    /**
     * @return string
     */
    public function getIssuerInstallmentId(): string
    {
        return $this->issuerInstallmentId;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Notification\Event
     */
    public function getNotificationEvent(): NotificationEvent
    {
        return $this->notificationEvent;
    }

    /**
     * @return string|null
     */
    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function isApplicationPreApproved(): bool
    {
        $event = $this->getNotificationEvent();

        return (
            $event->getEventType() === EventType::APPLICATION_PREAPPROVED &&
            $event->getApplicationStatus() === ApplicationStatus::PREAPPROVED &&
            $event->getInstallmentStatus() === InstallmentStatus::CREATED
        );
    }

    public function isSignatureCompleted(): bool
    {
        $event = $this->getNotificationEvent();

        return (
            $event->getEventType() === EventType::SIGNATURE_COMPLETE &&
            $event->getApplicationStatus() === ApplicationStatus::PREAPPROVED &&
            $event->getInstallmentStatus() === InstallmentStatus::CREATED
        );
    }

    public function isDocUploadCompleted(): bool
    {
        $event = $this->getNotificationEvent();

        return (
            $event->getEventType() === EventType::DOC_UPLOAD_COMPLETE &&
            $event->getApplicationStatus() === ApplicationStatus::PREAPPROVED &&
            $event->getInstallmentStatus() === InstallmentStatus::CREATED
        );
    }

    public function isApplicationCompleted(): bool
    {
        $event = $this->getNotificationEvent();

        return (
            $event->getEventType() === EventType::APPLICATION_COMPLETE &&
            $event->getApplicationStatus() === ApplicationStatus::COMPLETED &&
            $event->getInstallmentStatus() === InstallmentStatus::APPROVED
        );
    }

    public function isApplicationDeclined(): bool
    {
        $event = $this->getNotificationEvent();

        return (
            $event->getEventType() === EventType::APPLICATION_COMPLETE &&
            $event->getApplicationStatus() === ApplicationStatus::COMPLETED &&
            $event->getInstallmentStatus() === InstallmentStatus::DECLINED
        );
    }

    public function isRefundApproved(): bool
    {
        $event = $this->getNotificationEvent();

        return (
            $event->getEventType() === EventType::INSTALLMENT_REFUND &&
            $event->getApplicationStatus() === ApplicationStatus::COMPLETED &&
            $event->getInstallmentStatus() === InstallmentStatus::REFUND_APPROVED
        );
    }

    public function isRefundDeclined(): bool
    {
        $event = $this->getNotificationEvent();

        return (
            $event->getEventType() === EventType::INSTALLMENT_REFUND &&
            $event->getApplicationStatus() === ApplicationStatus::COMPLETED &&
            $event->getInstallmentStatus() === InstallmentStatus::REFUND_DECLINED
        );
    }

    public function isInstallmentCancelled(): bool
    {
        $event = $this->getNotificationEvent();

        return (
            $event->getEventType() === EventType::INSTALLMENT_CANCEL &&
            $event->getApplicationStatus() === ApplicationStatus::COMPLETED &&
            $event->getInstallmentStatus() === InstallmentStatus::CANCELLED
        );
    }

    public function toArray(): array
    {
        $arr = [
            'applicationId' => $this->getApplicationId(),
            'issuerInstallmentId' => $this->getIssuerInstallmentId(),
            'notificationEvent' => $this->getNotificationEvent(),
            'orderNumber' => $this->getOrderNumber(),
        ];

        return array_filter($arr);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Notification;

use Nextouch\Findomestic\Model\Plan\Details as Plan;

class Event
{
    private ?string $eventType;
    private ?string $installmentStatus;
    private ?string $applicationStatus;
    private ?Plan $plan;
    private ?string $lastUpdated;
    private ?string $notificationId;

    public function __construct(
        ?string $eventType = null,
        ?string $installmentStatus = null,
        ?string $applicationStatus = null,
        ?Plan $plan = null,
        ?string $lastUpdated = null,
        ?string $notificationId = null
    ) {
        $this->eventType = $eventType;
        $this->installmentStatus = $installmentStatus;
        $this->applicationStatus = $applicationStatus;
        $this->plan = $plan;
        $this->lastUpdated = $lastUpdated;
        $this->notificationId = $notificationId;
    }

    /**
     * @return string|null
     */
    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    /**
     * @return string|null
     */
    public function getInstallmentStatus(): ?string
    {
        return $this->installmentStatus;
    }

    /**
     * @return string|null
     */
    public function getApplicationStatus(): ?string
    {
        return $this->applicationStatus;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Plan\Details|null
     */
    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    /**
     * @return string|null
     */
    public function getLastUpdated(): ?string
    {
        return $this->lastUpdated;
    }

    /**
     * @return string|null
     */
    public function getNotificationId(): ?string
    {
        return $this->notificationId;
    }
}

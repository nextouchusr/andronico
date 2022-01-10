<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Appointment;

class TimeSlotRange
{
    private const DAYS_TO_SPEND_BEFORE_SHIPPING_STARTS = '3 days';
    private const DAYS_TO_SPEND_BEFORE_SHIPPING_STOPS = '15 days';
    private const DEFAULT_TIMESLOTS = ['09-13', '15-18'];

    private \DateTimeImmutable $dateStart;
    private \DateTimeImmutable $dateStop;
    private ?string $timeSlots;

    public function __construct(\DateTimeImmutable $dateStart, \DateTimeImmutable $dateStop, string $timeSlots = null)
    {
        $this->dateStart = $dateStart;
        $this->dateStop = $dateStop;
        $this->timeSlots = $timeSlots;
    }

    public function getDateStart(): \DateTimeImmutable
    {
        return $this->dateStart;
    }

    public function getDateStop(): \DateTimeImmutable
    {
        return $this->dateStop;
    }

    public function getTimeSlots(): ?string
    {
        return $this->timeSlots;
    }

    public static function fromToday(): self
    {
        $today = new \DateTimeImmutable();
        $dateStart = $today->add(\DateInterval::createFromDateString(self::DAYS_TO_SPEND_BEFORE_SHIPPING_STARTS));
        $dateStop = $dateStart->add(\DateInterval::createFromDateString(self::DAYS_TO_SPEND_BEFORE_SHIPPING_STOPS));
        $timeSlots = implode(',', self::DEFAULT_TIMESLOTS);

        return new self($dateStart, $dateStop, $timeSlots);
    }
}

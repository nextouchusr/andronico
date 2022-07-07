<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Appointment;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SlotResponse implements OutputInterface
{
    private string $date;
    private string $timeSlot;
    private int $slotsNumber;

    private function __construct(string $date, string $timeSlot, int $slotsNumber)
    {
        $this->date = $date;
        $this->timeSlot = $timeSlot;
        $this->slotsNumber = $slotsNumber;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getTimeSlot(): string
    {
        return $this->timeSlot;
    }

    /**
     * @return int
     */
    public function getSlotsNumber(): int
    {
        return $this->slotsNumber;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $date = (string) $propertyAccessor->getValue($object, 'date');
        $timeSlot = (string) $propertyAccessor->getValue($object, 'time_slot');
        $slotsNumber = (int) $propertyAccessor->getValue($object, 'slots_number');

        return new self($date, $timeSlot, $slotsNumber);
    }
}

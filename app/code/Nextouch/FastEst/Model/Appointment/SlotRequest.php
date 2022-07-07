<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Appointment;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Model\Directory\Place;

class SlotRequest implements InputInterface
{
    private const DATE_FORMAT = 'Y-m-d';

    private TimeSlotRange $timeSlotRange;
    private string $customerPostCode;
    private int $quantity;
    private int $placeId;
    private ?int $productId;
    private ?bool $assembly;
    private ?bool $disassembly;
    private ?bool $usedPick;

    private function __construct(
        TimeSlotRange $timeSlotRange,
        string $customerPostCode,
        int $quantity,
        int $placeId,
        int $productId = null,
        bool $assembly = null,
        bool $disassembly = null,
        bool $usedPick = null
    ) {
        $this->timeSlotRange = $timeSlotRange;
        $this->customerPostCode = $customerPostCode;
        $this->quantity = $quantity;
        $this->placeId = $placeId;
        $this->productId = $productId;
        $this->assembly = $assembly;
        $this->disassembly = $disassembly;
        $this->usedPick = $usedPick;
    }

    public function getTimeSlotRange(): TimeSlotRange
    {
        return $this->timeSlotRange;
    }

    public function getCustomerPostCode(): string
    {
        return $this->customerPostCode;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPlaceId(): int
    {
        return $this->placeId;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function hasAssembly(): ?bool
    {
        return $this->assembly;
    }

    public function hasDisassembly(): ?bool
    {
        return $this->disassembly;
    }

    public function hasUsedPick(): ?bool
    {
        return $this->usedPick;
    }

    public static function fromDomain(CartInformation $cart): self
    {
        return new self(
            TimeSlotRange::fromToday(),
            $cart->getCustomerPostCode(),
            $cart->getQuantity(),
            Place::HYPOTENUSE
        );
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->date_start = $this->getTimeSlotRange()->getDateStart()->format(self::DATE_FORMAT);
        $object->date_stop = $this->getTimeSlotRange()->getDateStop()->format(self::DATE_FORMAT);
        $object->time_slots = $this->getTimeSlotRange()->getTimeSlots();
        $object->customer_cap = $this->getCustomerPostCode();
        $object->quantity = $this->getQuantity();
        $object->product_place_id = $this->getPlaceId();
        $object->product_id = $this->getProductId();
        $object->incasso = $this->hasAssembly();
        $object->disincasso = $this->hasDisassembly();
        $object->used_pick = $this->hasUsedPick();

        return $object;
    }
}

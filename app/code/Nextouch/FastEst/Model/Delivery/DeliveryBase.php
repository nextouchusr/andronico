<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Delivery;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Model\Directory\Hour;
use Nextouch\FastEst\Model\Directory\Store;
use Nextouch\Sales\Api\Data\ShipmentInterface;

class DeliveryBase implements InputInterface
{
    private int $hourId;
    private int $storeId;
    private string $storeOrder;
    private float $storeCash;
    private string $storeNotes;
    private string $appointmentDate;
    private string $appointmentTimeSlot;
    private bool $isParked;

    private function __construct(
        int $hourId,
        int $storeId,
        string $storeOrder,
        float $storeCash,
        string $storeNotes,
        string $appointmentDate,
        string $appointmentTimeSlot,
        bool $isParked
    ) {
        $this->hourId = $hourId;
        $this->storeId = $storeId;
        $this->storeOrder = $storeOrder;
        $this->storeCash = $storeCash;
        $this->storeNotes = $storeNotes;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentTimeSlot = $appointmentTimeSlot;
        $this->isParked = $isParked;
    }

    public function getHourId(): int
    {
        return $this->hourId;
    }

    public function getStoreId(): int
    {
        return $this->storeId;
    }

    public function getStoreOrder(): string
    {
        return $this->storeOrder;
    }

    public function getStoreCash(): float
    {
        return $this->storeCash;
    }

    public function getStoreNotes(): string
    {
        return $this->storeNotes;
    }

    public function getAppointmentDate(): string
    {
        return $this->appointmentDate;
    }

    public function getAppointmentTimeSlot(): string
    {
        return $this->appointmentTimeSlot;
    }

    public function isParked(): bool
    {
        return $this->isParked;
    }

    /**
     * TODO: replace mock data
     * TODO: Manage parked deliveries, or those whose payment will take place in the future (Findomestic, Bank Transfer)
     * TODO: Save all orders as parked and use a cronjob to confirm parked deliveries
     */
    public static function fromDomain(ShipmentInterface $shipment): self
    {
        return new self(
            Hour::ANY,
            Store::WEB,
            strtoupper(uniqid()),
            0,
            $shipment->getOrder()->getDeliveryComment(),
            $shipment->getOrder()->getDeliveryDate(),
            $shipment->getOrder()->getDeliveryTime(),
            true
        );
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->hour_id = $this->getHourId();
        $object->store_id = $this->getStoreId();
        $object->store_order = $this->getStoreOrder();
        $object->store_cash = $this->getStoreCash();
        $object->store_notes = $this->getStoreNotes();
        $object->date_appointment = $this->getAppointmentDate();
        $object->time_slot = $this->getAppointmentTimeSlot();
        $object->is_parked = $this->isParked();

        return $object;
    }
}

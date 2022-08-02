<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Delivery;

use Magento\Framework\App\ObjectManager;
use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Helper\FastEstConfig;
use Nextouch\FastEst\Model\Directory\Hour;
use Nextouch\Sales\Api\Data\OrderInterface;

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

    public static function fromDomain(OrderInterface $order): self
    {
        $deliveryTime = preg_replace('/\s+/', '', $order->getDeliveryTime());
        $fastEstConfig = ObjectManager::getInstance()->get(FastEstConfig::class);

        return new self(
            Hour::ANY,
            $fastEstConfig->getStoreId(),
            $order->getIncrementId(),
            0,
            $order->getDeliveryComment(),
            $order->getDeliveryDate(),
            $deliveryTime,
            !$order->isPaid()
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

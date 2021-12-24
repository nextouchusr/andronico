<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Delivery;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ServiceList implements InputInterface, OutputInterface
{
    private bool $productInstall;
    private bool $usedPick;
    private bool $assembly;
    private bool $disassembly;
    private bool $atFloor;
    private bool $unpack;
    private bool $deliveryByAppointment;
    private bool $productPickup;
    private bool $productDelivery;
    private bool $noticeCall;

    private function __construct(
        bool $productInstall,
        bool $usedPick,
        bool $assembly,
        bool $disassembly,
        bool $atFloor,
        bool $unpack,
        bool $deliveryByAppointment,
        bool $productPickup,
        bool $productDelivery,
        bool $noticeCall
    ) {
        $this->productInstall = $productInstall;
        $this->usedPick = $usedPick;
        $this->assembly = $assembly;
        $this->disassembly = $disassembly;
        $this->atFloor = $atFloor;
        $this->unpack = $unpack;
        $this->deliveryByAppointment = $deliveryByAppointment;
        $this->productPickup = $productPickup;
        $this->productDelivery = $productDelivery;
        $this->noticeCall = $noticeCall;
    }

    public function hasProductInstall(): bool
    {
        return $this->productInstall;
    }

    public function hasUsedPick(): bool
    {
        return $this->usedPick;
    }

    public function hasAssembly(): bool
    {
        return $this->assembly;
    }

    public function hasDisassembly(): bool
    {
        return $this->disassembly;
    }

    public function hasAtFloor(): bool
    {
        return $this->atFloor;
    }

    public function hasUnpack(): bool
    {
        return $this->unpack;
    }

    public function hasDeliveryByAppointment(): bool
    {
        return $this->deliveryByAppointment;
    }

    public function hasProductPickup(): bool
    {
        return $this->productPickup;
    }

    public function hasProductDelivery(): bool
    {
        return $this->productDelivery;
    }

    public function hasNoticeCall(): bool
    {
        return $this->noticeCall;
    }

    public static function fromDomain(OrderItemInterface $orderItem): self
    {
        return new self(
            $orderItem->hasProductInstallService(),
            $orderItem->hasUsedPickService(),
            $orderItem->hasAssemblyService(),
            $orderItem->hasDisassemblyService(),
            $orderItem->hasAtFloorService(),
            $orderItem->hasUnpackService(),
            $orderItem->hasDeliveryByAppointmentService(),
            $orderItem->hasProductPickupService(),
            $orderItem->hasProductDeliveryService(),
            $orderItem->hasNoticeCallService()
        );
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $productInstall = (bool) $propertyAccessor->getValue($object, 'product_install');
        $usedPick = (bool) $propertyAccessor->getValue($object, 'used_pick');
        $assembly = (bool) $propertyAccessor->getValue($object, 'incasso');
        $disassembly = (bool) $propertyAccessor->getValue($object, 'disincasso');
        $atFloor = (bool) $propertyAccessor->getValue($object, 'service_atfloor');
        $unpack = (bool) $propertyAccessor->getValue($object, 'service_unpack');
        $deliveryByAppointment = (bool) $propertyAccessor->getValue($object, 'service_appointment');
        $productPickup = (bool) $propertyAccessor->getValue($object, 'service_pickup');
        $productDelivery = (bool) $propertyAccessor->getValue($object, 'service_delivery');
        $noticeCall = (bool) $propertyAccessor->getValue($object, 'service_call');

        return new self(
            $productInstall,
            $usedPick,
            $assembly,
            $disassembly,
            $atFloor,
            $unpack,
            $deliveryByAppointment,
            $productPickup,
            $productDelivery,
            $noticeCall
        );
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->product_install = (int) $this->hasProductInstall();
        $object->used_pick = (int) $this->hasUsedPick();
        $object->incasso = (int) $this->hasAssembly();
        $object->disincasso = (int) $this->hasDisassembly();
        $object->service_atfloor = (int) $this->hasAtFloor();
        $object->service_unpack = (int) $this->hasUnpack();
        $object->service_appointment = (int) $this->hasDeliveryByAppointment();
        $object->service_pickup = (int) $this->hasProductPickup();
        $object->service_delivery = (int) $this->hasProductDelivery();
        $object->service_call = (int) $this->hasNoticeCall();

        return $object;
    }
}

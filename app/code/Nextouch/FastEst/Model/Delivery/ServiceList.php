<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Delivery;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ServiceList implements InputInterface, OutputInterface
{
    private bool $productInstall; // Installazione prodotto
    private bool $usedPick; // Ritiro usato RAEE
    private bool $assembly; // Incasso
    private bool $disassembly; // Disincasso
    private bool $roadside; // Consegna bordo strada
    private bool $atFloor; // Consegna al piano
    private bool $wallmount; // Montaggio a muro
    private bool $axa; // Consegna in giornata
    private bool $appointment; // Consegna su appuntamento
    private bool $pickup; // Ritiro prodotto
    private bool $delivery; // Consegna prodotto (default)
    private bool $evening; // Consegna serale
    private bool $festive; // Consegna festivo
    private bool $bigtv; // Installazione TV di grandi dimensioni

    private function __construct(
        bool $productInstall,
        bool $usedPick,
        bool $assembly,
        bool $disassembly,
        bool $roadside,
        bool $atFloor,
        bool $wallmount,
        bool $axa,
        bool $appointment,
        bool $pickup,
        bool $delivery,
        bool $evening,
        bool $festive,
        bool $bigtv
    ) {
        $this->productInstall = $productInstall;
        $this->usedPick = $usedPick;
        $this->assembly = $assembly;
        $this->disassembly = $disassembly;
        $this->roadside = $roadside;
        $this->atFloor = $atFloor;
        $this->wallmount = $wallmount;
        $this->axa = $axa;
        $this->appointment = $appointment;
        $this->pickup = $pickup;
        $this->delivery = $delivery;
        $this->evening = $evening;
        $this->festive = $festive;
        $this->bigtv = $bigtv;
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

    public function hasRoadside(): bool
    {
        return $this->roadside;
    }

    public function hasAtFloor(): bool
    {
        return $this->atFloor;
    }

    public function hasWallmount(): bool
    {
        return $this->wallmount;
    }

    public function hasAxa(): bool
    {
        return $this->axa;
    }

    public function hasAppointment(): bool
    {
        return $this->appointment;
    }

    public function hasPickup(): bool
    {
        return $this->pickup;
    }

    public function hasDelivery(): bool
    {
        return $this->delivery;
    }

    public function hasEvening(): bool
    {
        return $this->evening;
    }

    public function hasFestive(): bool
    {
        return $this->festive;
    }

    public function hasBigtv(): bool
    {
        return $this->bigtv;
    }

    public static function fromDomain(OrderItemInterface $orderItem): self
    {
        return new self(
            $orderItem->hasProductInstall(),
            false, // TODO: replace with real data
            false, // TODO: replace with real data
            false, // TODO: replace with real data
            $orderItem->hasStreetLineDelivery(),
            $orderItem->hasFloorDelivery(),
            $orderItem->hasTvWallMounting(),
            $orderItem->hasUrgentDelivery(),
            $orderItem->hasAppointmentDelivery(),
            false, // TODO: replace with real data
            true,
            $orderItem->hasEveningDelivery(),
            $orderItem->hasSaturdayDelivery(),
            false, // TODO: replace with real data
        );
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $productInstall = (bool) $propertyAccessor->getValue($object, 'product_install');
        $usedPick = (bool) $propertyAccessor->getValue($object, 'used_pick');
        $assembly = (bool) $propertyAccessor->getValue($object, 'incasso');
        $disassembly = (bool) $propertyAccessor->getValue($object, 'disincasso');
        $roadside = (bool) $propertyAccessor->getValue($object, 'service_roadside');
        $atFloor = (bool) $propertyAccessor->getValue($object, 'service_atfloor');
        $wallmount = (bool) $propertyAccessor->getValue($object, 'service_wallmount');
        $axa = (bool) $propertyAccessor->getValue($object, 'service_axa');
        $appointment = (bool) $propertyAccessor->getValue($object, 'service_appointment');
        $pickup = (bool) $propertyAccessor->getValue($object, 'service_pickup');
        $delivery = (bool) $propertyAccessor->getValue($object, 'service_delivery');
        $evening = (bool) $propertyAccessor->getValue($object, 'service_evening');
        $festive = (bool) $propertyAccessor->getValue($object, 'service_festive');
        $bigtv = (bool) $propertyAccessor->getValue($object, 'service_bigtv');

        return new self(
            $productInstall,
            $usedPick,
            $assembly,
            $disassembly,
            $roadside,
            $atFloor,
            $wallmount,
            $axa,
            $appointment,
            $pickup,
            $delivery,
            $evening,
            $festive,
            $bigtv
        );
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->product_install = (int) $this->hasProductInstall();
        $object->used_pick = (int) $this->hasUsedPick();
        $object->incasso = (int) $this->hasAssembly();
        $object->disincasso = (int) $this->hasDisassembly();
        $object->service_roadside = (int) $this->hasRoadside();
        $object->service_atfloor = (int) $this->hasAtFloor();
        $object->service_wallmount = (int) $this->hasWallmount();
        $object->service_axa = (int) $this->hasAxa();
        $object->service_appointment = (int) $this->hasAppointment();
        $object->service_pickup = (int) $this->hasPickup();
        $object->service_delivery = (int) $this->hasDelivery();
        $object->service_evening = (int) $this->hasEvening();
        $object->service_festive = (int) $this->hasFestive();
        $object->service_bigtv = (int) $this->hasBigtv();

        return $object;
    }
}

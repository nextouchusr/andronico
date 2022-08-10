<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Delivery;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ServiceList implements InputInterface, OutputInterface
{
    private const SMARTTV_SERVICE = 'SMARTTV';
    private const SBARWIFI_SERVICE = 'SBARWIFI';

    private bool $productInstall; // Installazione prodotto
    private bool $usedPick; // Ritiro usato RAEE
    private bool $assembly; // Incasso
    private bool $roadside; // Consegna bordo strada
    private bool $atFloor; // Consegna al piano
    private bool $wallmount; // Montaggio a muro
    private bool $appointment; // Consegna su appuntamento
    private bool $delivery; // Consegna prodotto (default)
    private bool $evening; // Consegna serale
    private string $others; // Servizi committente (separati da virgola)
    private bool $gasCert; // Certificazione gas
    private string $sourceServices; // Servizi originali del committente (separati da virgola)

    private function __construct(
        bool $productInstall,
        bool $usedPick,
        bool $assembly,
        bool $roadside,
        bool $atFloor,
        bool $wallmount,
        bool $appointment,
        bool $delivery,
        bool $evening,
        string $others,
        bool $gasCert,
        string $sourceServices
    ) {
        $this->productInstall = $productInstall;
        $this->usedPick = $usedPick;
        $this->assembly = $assembly;
        $this->roadside = $roadside;
        $this->atFloor = $atFloor;
        $this->wallmount = $wallmount;
        $this->appointment = $appointment;
        $this->delivery = $delivery;
        $this->evening = $evening;
        $this->others = $others;
        $this->gasCert = $gasCert;
        $this->sourceServices = $sourceServices;
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

    public function hasAppointment(): bool
    {
        return $this->appointment;
    }

    public function hasDelivery(): bool
    {
        return $this->delivery;
    }

    public function hasEvening(): bool
    {
        return $this->evening;
    }

    public function getOthers(): string
    {
        return $this->others;
    }

    public function hasGasCert(): bool
    {
        return $this->gasCert;
    }

    public function getSourceServices(): string
    {
        return $this->sourceServices;
    }

    public static function fromDomain(OrderItemInterface $orderItem): self
    {
        $productInstall = (
            $orderItem->hasProductInstall() ||
            $orderItem->hasStandardTvInstallation() ||
            $orderItem->hasConnectivityAndTvDemonstration() ||
            $orderItem->hasPeripheralInstallationToTv() ||
            $orderItem->hasTvWallMounting() ||
            $orderItem->hasAirConditioningInstallationMono() ||
            $orderItem->hasAirConditioningInstallationDual() ||
            $orderItem->hasAirConditioningInstallationTrial()
        );

        $others = [];
        if ($orderItem->hasConnectivityAndTvDemonstration()) {
            $others[] = self::SMARTTV_SERVICE;
        }

        if ($orderItem->hasPeripheralInstallationToTv()) {
            $others[] = self::SBARWIFI_SERVICE;
        }

        return new self(
            $productInstall,
            $orderItem->hasRaeeReturn(),
            $orderItem->hasBuiltInProduct(),
            $orderItem->hasStreetLineDelivery(),
            $orderItem->hasFloorDelivery(),
            $orderItem->hasTvWallMounting(),
            (bool) $orderItem->getOrder()->getDeliveryDate(),
            true,
            $orderItem->hasEveningDelivery(),
            implode(',', $others),
            $orderItem->hasGasCertification(),
            $orderItem->getSelectedOptionLabels()
        );
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $productInstall = (bool) $propertyAccessor->getValue($object, 'product_install');
        $usedPick = (bool) $propertyAccessor->getValue($object, 'used_pick');
        $assembly = (bool) $propertyAccessor->getValue($object, 'incasso');
        $roadside = (bool) $propertyAccessor->getValue($object, 'service_roadside');
        $atFloor = (bool) $propertyAccessor->getValue($object, 'service_atfloor');
        $wallmount = (bool) $propertyAccessor->getValue($object, 'service_wallmount');
        $appointment = (bool) $propertyAccessor->getValue($object, 'service_appointment');
        $delivery = (bool) $propertyAccessor->getValue($object, 'service_delivery');
        $evening = (bool) $propertyAccessor->getValue($object, 'service_evening');
        $others = (string) $propertyAccessor->getValue($object, 'service_others');
        $gasCert = (bool) $propertyAccessor->getValue($object, 'service_gascert');
        $sourceServices = (string) $propertyAccessor->getValue($object, 'src_services');

        return new self(
            $productInstall,
            $usedPick,
            $assembly,
            $roadside,
            $atFloor,
            $wallmount,
            $appointment,
            $delivery,
            $evening,
            $others,
            $gasCert,
            $sourceServices
        );
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->product_install = (int) $this->hasProductInstall();
        $object->used_pick = (int) $this->hasUsedPick();
        $object->incasso = (int) $this->hasAssembly();
        $object->service_roadside = (int) $this->hasRoadside();
        $object->service_atfloor = (int) $this->hasAtFloor();
        $object->service_wallmount = (int) $this->hasWallmount();
        $object->service_appointment = (int) $this->hasAppointment();
        $object->service_delivery = (int) $this->hasDelivery();
        $object->service_evening = (int) $this->hasEvening();
        $object->service_others = $this->getOthers();
        $object->service_gascert = (int) $this->hasGasCert();
        $object->src_services = $this->getSourceServices();

        return $object;
    }
}

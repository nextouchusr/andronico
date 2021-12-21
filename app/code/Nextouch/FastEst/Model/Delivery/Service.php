<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Delivery;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;

class Service implements InputInterface
{
    private bool $productInstall;
    private bool $usedPick;
    private bool $assembly;
    private bool $disassembly;
    private bool $exclusive;
    private bool $reverseDoors;
    private bool $roadSide;
    private bool $atFloor;
    private bool $unpack;
    private bool $outArea;
    private bool $disadvantagedArea;
    private bool $wallMounting;
    private bool $deliveryAB;
    private bool $sideBySide;
    private bool $extra1;
    private bool $extra2;
    private bool $extra3;
    private bool $extra4;
    private bool $sameDayDelivery;
    private bool $deliveryByAppointment;
    private bool $productPickup;
    private bool $productDelivery;
    private bool $inspection;
    private bool $eveningDelivery;
    private bool $sundayDelivery;
    private bool $holidayDelivery;
    private string $others;
    private bool $additionalDelivery;
    private bool $onlyService;
    private bool $junctionKit;
    private bool $gasCertification;
    private bool $waterConnection;
    private bool $bigTV;
    private bool $noticeCall;

    public function __construct(
        bool $productInstall,
        bool $usedPick,
        bool $assembly,
        bool $disassembly,
        bool $exclusive,
        bool $reverseDoors,
        bool $roadSide,
        bool $atFloor,
        bool $unpack,
        bool $outArea,
        bool $disadvantagedArea,
        bool $wallMounting,
        bool $deliveryAB,
        bool $sideBySide,
        bool $extra1,
        bool $extra2,
        bool $extra3,
        bool $extra4,
        bool $sameDayDelivery,
        bool $deliveryByAppointment,
        bool $productPickup,
        bool $productDelivery,
        bool $inspection,
        bool $eveningDelivery,
        bool $sundayDelivery,
        bool $holidayDelivery,
        string $others,
        bool $additionalDelivery,
        bool $onlyService,
        bool $junctionKit,
        bool $gasCertification,
        bool $waterConnection,
        bool $bigTV,
        bool $noticeCall
    ) {
        $this->productInstall = $productInstall;
        $this->usedPick = $usedPick;
        $this->assembly = $assembly;
        $this->disassembly = $disassembly;
        $this->exclusive = $exclusive;
        $this->reverseDoors = $reverseDoors;
        $this->roadSide = $roadSide;
        $this->atFloor = $atFloor;
        $this->unpack = $unpack;
        $this->outArea = $outArea;
        $this->disadvantagedArea = $disadvantagedArea;
        $this->wallMounting = $wallMounting;
        $this->deliveryAB = $deliveryAB;
        $this->sideBySide = $sideBySide;
        $this->extra1 = $extra1;
        $this->extra2 = $extra2;
        $this->extra3 = $extra3;
        $this->extra4 = $extra4;
        $this->sameDayDelivery = $sameDayDelivery;
        $this->deliveryByAppointment = $deliveryByAppointment;
        $this->productPickup = $productPickup;
        $this->productDelivery = $productDelivery;
        $this->inspection = $inspection;
        $this->eveningDelivery = $eveningDelivery;
        $this->sundayDelivery = $sundayDelivery;
        $this->holidayDelivery = $holidayDelivery;
        $this->others = $others;
        $this->additionalDelivery = $additionalDelivery;
        $this->onlyService = $onlyService;
        $this->junctionKit = $junctionKit;
        $this->gasCertification = $gasCertification;
        $this->waterConnection = $waterConnection;
        $this->bigTV = $bigTV;
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

    public function hasExclusive(): bool
    {
        return $this->exclusive;
    }

    public function hasReverseDoors(): bool
    {
        return $this->reverseDoors;
    }

    public function hasRoadSide(): bool
    {
        return $this->roadSide;
    }

    public function hasAtFloor(): bool
    {
        return $this->atFloor;
    }

    public function hasUnpack(): bool
    {
        return $this->unpack;
    }

    public function hasOutArea(): bool
    {
        return $this->outArea;
    }

    public function hasDisadvantagedArea(): bool
    {
        return $this->disadvantagedArea;
    }

    public function hasWallMounting(): bool
    {
        return $this->wallMounting;
    }

    public function hasDeliveryAB(): bool
    {
        return $this->deliveryAB;
    }

    public function hasSideBySide(): bool
    {
        return $this->sideBySide;
    }

    public function hasExtra1(): bool
    {
        return $this->extra1;
    }

    public function hasExtra2(): bool
    {
        return $this->extra2;
    }

    public function hasExtra3(): bool
    {
        return $this->extra3;
    }

    public function hasExtra4(): bool
    {
        return $this->extra4;
    }

    public function hasSameDayDelivery(): bool
    {
        return $this->sameDayDelivery;
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

    public function hasInspection(): bool
    {
        return $this->inspection;
    }

    public function hasEveningDelivery(): bool
    {
        return $this->eveningDelivery;
    }

    public function hasSundayDelivery(): bool
    {
        return $this->sundayDelivery;
    }

    public function hasHolidayDelivery(): bool
    {
        return $this->holidayDelivery;
    }

    public function getOthers(): string
    {
        return $this->others;
    }

    public function hasAdditionalDelivery(): bool
    {
        return $this->additionalDelivery;
    }

    public function hasOnlyService(): bool
    {
        return $this->onlyService;
    }

    public function hasJunctionKit(): bool
    {
        return $this->junctionKit;
    }

    public function hasGasCertification(): bool
    {
        return $this->gasCertification;
    }

    public function hasWaterConnection(): bool
    {
        return $this->waterConnection;
    }

    public function hasBigTV(): bool
    {
        return $this->bigTV;
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
            false,
            false,
            false,
            $orderItem->hasAtFloorService(),
            $orderItem->hasUnpackService(),
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            $orderItem->hasDeliveryByAppointmentService(),
            $orderItem->hasProductPickupService(),
            $orderItem->hasProductDeliveryService(),
            false,
            false,
            false,
            false,
            '',
            false,
            false,
            false,
            false,
            false,
            false,
            $orderItem->hasNoticeCallService()
        );
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->services = new \stdClass();
        $object->services->product_install = (int) $this->hasProductInstall();
        $object->services->used_pick = (int) $this->hasUsedPick();
        $object->services->incasso = (int) $this->hasAssembly();
        $object->services->disincasso = (int) $this->hasDisassembly();
        $object->services->service_exclusive = (int) $this->hasExclusive();
        $object->services->service_revport = (int) $this->hasReverseDoors();
        $object->services->service_roadside = (int) $this->hasRoadSide();
        $object->services->service_atfloor = (int) $this->hasAtFloor();
        $object->services->service_unpack = (int) $this->hasUnpack();
        $object->services->service_outarea = (int) $this->hasOutArea();
        $object->services->service_disadvantaged = (int) $this->hasDisadvantagedArea();
        $object->services->service_wallmount = (int) $this->hasWallMounting();
        $object->services->service_axb = (int) $this->hasDeliveryAB();
        $object->services->service_sbs = (int) $this->hasSideBySide();
        $object->services->service_extra1 = (int) $this->hasExtra1();
        $object->services->service_extra2 = (int) $this->hasExtra2();
        $object->services->service_extra3 = (int) $this->hasExtra3();
        $object->services->service_extra4 = (int) $this->hasExtra4();
        $object->services->service_axa = (int) $this->hasSameDayDelivery();
        $object->services->service_appointment = (int) $this->hasDeliveryByAppointment();
        $object->services->service_pickup = (int) $this->hasProductPickup();
        $object->services->service_delivery = (int) $this->hasProductDelivery();
        $object->services->service_inspection = (int) $this->hasInspection();
        $object->services->service_evening = (int) $this->hasEveningDelivery();
        $object->services->service_sunday = (int) $this->hasSundayDelivery();
        $object->services->service_festive = (int) $this->hasHolidayDelivery();
        $object->services->service_others = $this->getOthers();
        $object->services->service_deliveryadd = (int) $this->hasAdditionalDelivery();
        $object->services->service_onlyservice = (int) $this->hasOnlyService();
        $object->services->service_kitjunc = (int) $this->hasJunctionKit();
        $object->services->service_gasvert = (int) $this->hasGasCertification();
        $object->services->service_waterconn = (int) $this->hasWaterConnection();
        $object->services->service_bigtv = (int) $this->hasBigTV();
        $object->services->service_call = (int) $this->hasNoticeCall();

        return $object;
    }
}

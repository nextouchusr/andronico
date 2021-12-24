<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order;

use Nextouch\Sales\Api\Data\OrderAddressInterface;

class Address extends \Magento\Sales\Model\Order\Address implements OrderAddressInterface
{
    public function getStreetAsLine(): string
    {
        return $this->getStreetLine(1);
    }

    public function getRegionCode(): string
    {
        return (string) parent::getRegionCode();
    }

    public function getPostcode(): string
    {
        return (string) $this->getData(self::POSTCODE);
    }

    public function getMobilePhone(): string
    {
        return (string) $this->getData(self::MOBILE_PHONE);
    }

    public function setMobilePhone(string $mobilePhone): self
    {
        $this->setData(self::MOBILE_PHONE, $mobilePhone);

        return $this;
    }

    public function isLimitedTrafficZone(): bool
    {
        return (bool) $this->getData(self::LIMITED_TRAFFIC_ZONE);
    }

    public function setLimitedTrafficZone(bool $limitedTrafficZone): self
    {
        $this->setData(self::LIMITED_TRAFFIC_ZONE, $limitedTrafficZone);

        return $this;
    }

    public function getFloor(): int
    {
        return (int) $this->getData(self::FLOOR);
    }

    public function setFloor(int $floor): self
    {
        $this->setData(self::FLOOR, $floor);

        return $this;
    }

    public function hasStair(): bool
    {
        return (bool) $this->getData(self::STAIR);
    }

    public function setStair(bool $stair): self
    {
        $this->setData(self::STAIR, $stair);

        return $this;
    }

    public function hasLift(): bool
    {
        return (bool) $this->getData(self::LIFT);
    }

    public function setLift(bool $lift): self
    {
        $this->setData(self::LIFT, $lift);

        return $this;
    }

    public function getVatId(): string
    {
        return (string) $this->getData(self::VAT_ID);
    }
}

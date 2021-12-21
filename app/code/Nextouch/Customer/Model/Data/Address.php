<?php
declare(strict_types=1);

namespace Nextouch\Customer\Model\Data;

use Nextouch\Customer\Api\Data\AddressInterface;

class Address extends \Magento\Customer\Model\Data\Address implements AddressInterface
{
    public function getMobilePhone(): string
    {
        return (string) $this->_get(self::MOBILE_PHONE);
    }

    public function setMobilePhone(string $mobilePhone): self
    {
        $this->setData(self::MOBILE_PHONE, $mobilePhone);

        return $this;
    }

    public function isLimitedTrafficZone(): bool
    {
        return (bool) $this->_get(self::LIMITED_TRAFFIC_ZONE);
    }

    public function setLimitedTrafficZone(bool $limitedTrafficZone): self
    {
        $this->setData(self::LIMITED_TRAFFIC_ZONE, $limitedTrafficZone);

        return $this;
    }

    public function getFloor(): int
    {
        return (int) $this->_get(self::FLOOR);
    }

    public function setFloor(int $floor): self
    {
        $this->setData(self::FLOOR, $floor);

        return $this;
    }

    public function hasStair(): bool
    {
        return (bool) $this->_get(self::STAIR);
    }

    public function setStair(bool $stair): self
    {
        $this->setData(self::STAIR, $stair);

        return $this;
    }

    public function hasLift(): bool
    {
        return (bool) $this->_get(self::LIFT);
    }

    public function setLift(bool $lift): self
    {
        $this->setData(self::LIFT, $lift);

        return $this;
    }
}

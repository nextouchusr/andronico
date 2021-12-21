<?php
declare(strict_types=1);

namespace Nextouch\Customer\Api\Data;

/**
 * @api
 */
interface AddressInterface extends \Magento\Customer\Api\Data\AddressInterface
{
    public const MOBILE_PHONE = 'mobile_phone';
    public const LIMITED_TRAFFIC_ZONE = 'limited_traffic_zone';
    public const FLOOR = 'floor';
    public const STAIR = 'stair';
    public const LIFT = 'lift';

    /**
     * @return string
     */
    public function getMobilePhone(): string;

    /**
     * @param string $mobilePhone
     * @return AddressInterface
     */
    public function setMobilePhone(string $mobilePhone): self;

    /**
     * @return bool
     */
    public function isLimitedTrafficZone(): bool;

    /**
     * @param bool $limitedTrafficZone
     * @return AddressInterface
     */
    public function setLimitedTrafficZone(bool $limitedTrafficZone): self;

    /**
     * @return int
     */
    public function getFloor(): int;

    /**
     * @param int $floor
     * @return AddressInterface
     */
    public function setFloor(int $floor): self;

    /**
     * @return bool
     */
    public function hasStair(): bool;

    /**
     * @param bool $stair
     * @return AddressInterface
     */
    public function setStair(bool $stair): self;

    /**
     * @return bool
     */
    public function hasLift(): bool;

    /**
     * @param bool $lift
     * @return AddressInterface
     */
    public function setLift(bool $lift): self;
}

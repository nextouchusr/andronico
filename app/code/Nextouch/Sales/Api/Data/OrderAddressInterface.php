<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

/**
 * @api
 */
interface OrderAddressInterface extends \Magento\Sales\Api\Data\OrderAddressInterface
{
    public const MOBILE_PHONE = 'mobile_phone';
    public const LIMITED_TRAFFIC_ZONE = 'limited_traffic_zone';
    public const FLOOR = 'floor';
    public const STAIR = 'stair';
    public const LIFT = 'lift';

    /**
     * @return string
     */
    public function getStreetAsLine(): string;

    /**
     * @return string
     */
    public function getRegionCode(): string;

    /**
     * @return string
     */
    public function getPostcode(): string;

    /**
     * @return string
     */
    public function getMobilePhone(): string;

    /**
     * @param string $mobilePhone
     * @return OrderAddressInterface
     */
    public function setMobilePhone(string $mobilePhone): self;

    /**
     * @return bool
     */
    public function isLimitedTrafficZone(): bool;

    /**
     * @param bool $limitedTrafficZone
     * @return OrderAddressInterface
     */
    public function setLimitedTrafficZone(bool $limitedTrafficZone): self;

    /**
     * @return int
     */
    public function getFloor(): int;

    /**
     * @param int $floor
     * @return OrderAddressInterface
     */
    public function setFloor(int $floor): self;

    /**
     * @return bool
     */
    public function hasStair(): bool;

    /**
     * @param bool $stair
     * @return OrderAddressInterface
     */
    public function setStair(bool $stair): self;

    /**
     * @return bool
     */
    public function hasLift(): bool;

    /**
     * @param bool $lift
     * @return OrderAddressInterface
     */
    public function setLift(bool $lift): self;

    /**
     * @return string
     */
    public function getVatId(): string;
}

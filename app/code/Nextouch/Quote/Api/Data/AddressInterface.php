<?php
declare(strict_types=1);

namespace Nextouch\Quote\Api\Data;

/**
 * @api
 */
interface AddressInterface extends \Magento\Quote\Api\Data\AddressInterface
{
    public const PEC = 'pec';
    public const SDI_CODE = 'sdi_code';
    public const INVOICE_TYPE = 'invoice_type';
    public const MOBILE_PHONE = 'mobile_phone';
    public const FISCAL_CODE = 'fiscal_code';
    public const LIMITED_TRAFFIC_ZONE = 'limited_traffic_zone';
    public const FLOOR = 'floor';
    public const STAIR = 'stair';
    public const LIFT = 'lift';

    /**
     * @return string
     */
    public function getPec(): string;

    /**
     * @param string $pec
     * @return AddressInterface
     */
    public function setPec(string $pec): self;

    /**
     * @return string
     */
    public function getSdiCode(): string;

    /**
     * @param string $sdiCode
     * @return AddressInterface
     */
    public function setSdiCode(string $sdiCode): self;

    /**
     * @return string
     */
    public function getInvoiceType(): string;

    /**
     * @param string $invoiceType
     * @return AddressInterface
     */
    public function setInvoiceType(string $invoiceType): self;

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
     * @return string
     */
    public function getFiscalCode(): string;

    /**
     * @param string $fiscalCode
     * @return AddressInterface
     */
    public function setFiscalCode(string $fiscalCode): self;

    /**
     * @return bool
     */
    public function getLimitedTrafficZone(): bool;

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
    public function getStair(): bool;

    /**
     * @param bool $stair
     * @return AddressInterface
     */
    public function setStair(bool $stair): self;

    /**
     * @return bool
     */
    public function getLift(): bool;

    /**
     * @param bool $lift
     * @return AddressInterface
     */
    public function setLift(bool $lift): self;
}

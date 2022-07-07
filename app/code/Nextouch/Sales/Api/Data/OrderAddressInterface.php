<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

/**
 * @api
 */
interface OrderAddressInterface extends \Magento\Sales\Api\Data\OrderAddressInterface
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
     * @return bool
     */
    public function isCompany(): bool;

    /**
     * @return string
     */
    public function getPec(): string;

    /**
     * @param string $pec
     * @return OrderAddressInterface
     */
    public function setPec(string $pec): self;

    /**
     * @return string
     */
    public function getSdiCode(): string;

    /**
     * @param string $sdiCode
     * @return OrderAddressInterface
     */
    public function setSdiCode(string $sdiCode): self;

    /**
     * @return string
     */
    public function getInvoiceType(): string;

    /**
     * @param string $invoiceType
     * @return OrderAddressInterface
     */
    public function setInvoiceType(string $invoiceType): self;

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
     * @return string
     */
    public function getFiscalCode(): string;

    /**
     * @param string $fiscalCode
     * @return OrderAddressInterface
     */
    public function setFiscalCode(string $fiscalCode): self;

    /**
     * @return bool
     */
    public function getLimitedTrafficZone(): bool;

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
    public function getStair(): bool;

    /**
     * @param bool $stair
     * @return OrderAddressInterface
     */
    public function setStair(bool $stair): self;

    /**
     * @return bool
     */
    public function getLift(): bool;

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

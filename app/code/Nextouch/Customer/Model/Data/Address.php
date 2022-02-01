<?php
declare(strict_types=1);

namespace Nextouch\Customer\Model\Data;

use Nextouch\Customer\Api\Data\AddressInterface;

class Address extends \Magento\Customer\Model\Data\Address implements AddressInterface
{
    public function isCompany(): bool
    {
        return $this->getCompany() !== null;
    }

    public function getPec(): string
    {
        return (string) $this->_get(self::PEC);
    }

    public function setPec(string $pec): self
    {
        $this->setData(self::PEC, $pec);

        return $this;
    }

    public function getSdiCode(): string
    {
        return (string) $this->_get(self::SDI_CODE);
    }

    public function setSdiCode(string $sdiCode): self
    {
        $this->setData(self::SDI_CODE, $sdiCode);

        return $this;
    }

    public function getInvoiceType(): string
    {
        return (string) $this->_get(self::INVOICE_TYPE);
    }

    public function setInvoiceType(string $invoiceType): self
    {
        $this->setData(self::INVOICE_TYPE, $invoiceType);

        return $this;
    }

    public function getMobilePhone(): string
    {
        return (string) $this->_get(self::MOBILE_PHONE);
    }

    public function setMobilePhone(string $mobilePhone): self
    {
        $this->setData(self::MOBILE_PHONE, $mobilePhone);

        return $this;
    }

    public function getFiscalCode(): string
    {
        return (string) $this->_get(self::FISCAL_CODE);
    }

    public function setFiscalCode(string $fiscalCode): self
    {
        $this->setData(self::FISCAL_CODE, $fiscalCode);

        return $this;
    }

    public function getLimitedTrafficZone(): bool
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

    public function getStair(): bool
    {
        return (bool) $this->_get(self::STAIR);
    }

    public function setStair(bool $stair): self
    {
        $this->setData(self::STAIR, $stair);

        return $this;
    }

    public function getLift(): bool
    {
        return (bool) $this->_get(self::LIFT);
    }

    public function setLift(bool $lift): self
    {
        $this->setData(self::LIFT, $lift);

        return $this;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Order;

use Nextouch\Customer\Model\Config\Address\InvoiceTypes;
use Nextouch\Sales\Api\Data\OrderAddressInterface;

class InvoiceInfo
{
    private const PRIVATE_COMPANY = 'private';
    private const BUSINESS_COMPANY = 'company';

    private const INVOICE_TYPE = 'INVOICE';
    private const RECEIPT_TYPE = 'RECEPIT';

    private string $privateCompany;
    private string $vatId;
    private string $fiscalCode;
    private string $sdi;
    private string $pec;
    private string $type;

    private function __construct(
        string $privateCompany,
        string $vatId,
        string $fiscalCode,
        string $sdi,
        string $pec,
        string $type
    ) {
        $this->privateCompany = $privateCompany;
        $this->vatId = $vatId;
        $this->fiscalCode = $fiscalCode;
        $this->sdi = $sdi;
        $this->pec = $pec;
        $this->type = $type;
    }

    public function getPrivateCompany(): string
    {
        return $this->privateCompany;
    }

    public function getVatId(): string
    {
        return $this->vatId;
    }

    public function getFiscalCode(): string
    {
        return $this->fiscalCode;
    }

    public function getSdi(): string
    {
        return $this->sdi;
    }

    public function getPec(): string
    {
        return $this->pec;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public static function fromDomain(OrderAddressInterface $address): self
    {
        $privateCompany = $address->getCompany() ? self::PRIVATE_COMPANY : self::BUSINESS_COMPANY;
        $type = $address->getInvoiceType() === InvoiceTypes::INVOICE_TYPE ? self::INVOICE_TYPE : self::RECEIPT_TYPE;

        return new self(
            $privateCompany,
            $address->getVatId(),
            $address->getFiscalCode(),
            $address->getSdiCode(),
            $address->getPec(),
            $type
        );
    }
}

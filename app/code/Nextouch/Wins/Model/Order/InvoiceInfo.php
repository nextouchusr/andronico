<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Order;

use Magento\Sales\Api\Data\OrderAddressInterface;
use Nextouch\Wins\Api\Data\ArrayableInterface;

class InvoiceInfo implements ArrayableInterface
{
    private const PRIVATE_COMPANY = 'private';
    private const BUSINESS_COMPANY = 'company';

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
        $isCompany = $address->getCompany() !== null;
        $privateCompany = $isCompany ? self::BUSINESS_COMPANY : self::PRIVATE_COMPANY;

        return new self(
            $privateCompany,
            (string) $address->getVatId(),
            (string) $address->getFiscalCode(),
            (string) $address->getSdiCode(),
            (string) $address->getPec(),
            strtoupper((string) $address->getInvoiceType())
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['private_company'],
            (string) $data['vat_id'],
            (string) $data['fiscal_code'],
            (string) $data['sdi'],
            (string) $data['pec'],
            (string) $data['type'],
        );
    }

    public function toArray(): array
    {
        return [
            'private_company' => $this->getPrivateCompany(),
            'vat_id' => $this->getVatId(),
            'fiscal_code' => $this->getFiscalCode(),
            'sdi' => $this->getSdi(),
            'pec' => $this->getPec(),
            'type' => $this->getType(),
        ];
    }
}

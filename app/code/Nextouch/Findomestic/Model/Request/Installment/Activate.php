<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Request\Installment;

use Nextouch\Findomestic\Api\Data\InputInterface;

class Activate implements InputInterface
{
    private string $issuerInstallmentId;
    private string $payerId;
    private string $orderNumber;
    private string $shipmentDate;
    private ?string $invoiceNumber;

    public function __construct(
        string $issuerInstallmentId,
        string $payerId,
        string $orderNumber,
        string $shipmentDate,
        ?string $invoiceNumber = null
    ) {
        $this->issuerInstallmentId = $issuerInstallmentId;
        $this->payerId = $payerId;
        $this->orderNumber = $orderNumber;
        $this->shipmentDate = $shipmentDate;
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @return string
     */
    public function getIssuerInstallmentId(): string
    {
        return $this->issuerInstallmentId;
    }

    /**
     * @return string
     */
    public function getPayerId(): string
    {
        return $this->payerId;
    }

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * @return string
     */
    public function getShipmentDate(): string
    {
        return $this->shipmentDate;
    }

    /**
     * @return string|null
     */
    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function toArray(): array
    {
        $arr = [
            'payerId' => $this->getPayerId(),
            'orderNumber' => $this->getOrderNumber(),
            'shipmentDate' => $this->getShipmentDate(),
            'invoiceNumber' => $this->getInvoiceNumber(),
        ];

        return array_filter($arr);
    }
}

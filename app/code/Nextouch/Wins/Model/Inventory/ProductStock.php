<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Inventory;

use Nextouch\Wins\Api\Data\ArrayableInterface;

class ProductStock implements ArrayableInterface
{
    private string $productCode;
    private string $spinCode;
    private string $spinDescription;
    private int $availability;
    private int $qty;
    private int $preorderQty;
    private int $orderQty;
    private int $repairQty;
    private int $apartedQty;
    private int $initialQty;
    private int $minimalSupply;
    private int $maximalSupply;

    private function __construct(
        string $productCode,
        string $spinCode,
        string $spinDescription,
        int $availability,
        int $qty,
        int $preorderQty,
        int $orderQty,
        int $repairQty,
        int $apartedQty,
        int $initialQty,
        int $minimalSupply,
        int $maximalSupply
    ) {
        $this->productCode = $productCode;
        $this->spinCode = $spinCode;
        $this->spinDescription = $spinDescription;
        $this->availability = $availability;
        $this->qty = $qty;
        $this->preorderQty = $preorderQty;
        $this->orderQty = $orderQty;
        $this->repairQty = $repairQty;
        $this->apartedQty = $apartedQty;
        $this->initialQty = $initialQty;
        $this->minimalSupply = $minimalSupply;
        $this->maximalSupply = $maximalSupply;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function getSpinCode(): string
    {
        return $this->spinCode;
    }

    public function getSpinDescription(): string
    {
        return $this->spinDescription;
    }

    public function getAvailability(): int
    {
        return $this->availability;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function getPreorderQty(): int
    {
        return $this->preorderQty;
    }

    public function getOrderQty(): int
    {
        return $this->orderQty;
    }

    public function getRepairQty(): int
    {
        return $this->repairQty;
    }

    public function getApartedQty(): int
    {
        return $this->apartedQty;
    }

    public function getInitialQty(): int
    {
        return $this->initialQty;
    }

    public function getMinimalSupply(): int
    {
        return $this->minimalSupply;
    }

    public function getMaximalSupply(): int
    {
        return $this->maximalSupply;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['ProductCode'],
            (string) $data['SpinCode'],
            (string) $data['SpinDescription'],
            (int) $data['Availability'],
            (int) $data['Quantity'],
            (int) $data['PreorderQuantity'],
            (int) $data['OrderQuantity'],
            (int) $data['RepairQuantity'],
            (int) $data['ApartedQuantity'],
            (int) $data['InitialQuantity'],
            (int) $data['MinimalSupply'],
            (int) $data['MaximalSupply'],
        );
    }

    public function toArray(): array
    {
        return [
            'ProductCode' => $this->getProductCode(),
            'SpinCode' => $this->getSpinCode(),
            'SpinDescription' => $this->getSpinDescription(),
            'Availability' => $this->getAvailability(),
            'Quantity' => $this->getQty(),
            'PreorderQuantity' => $this->getPreorderQty(),
            'OrderQuantity' => $this->getOrderQty(),
            'RepairQuantity' => $this->getRepairQty(),
            'ApartedQuantity' => $this->getApartedQty(),
            'InitialQuantity' => $this->getInitialQty(),
            'MinimalSupply' => $this->getMinimalSupply(),
            'MaximalSupply' => $this->getMaximalSupply(),
        ];
    }
}

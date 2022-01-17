<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Appointment;

class CartInformation
{
    private string $customerPostCode;
    private int $quantity;

    public function __construct(string $customerPostCode, int $quantity)
    {
        $this->customerPostCode = $customerPostCode;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getCustomerPostCode(): string
    {
        return $this->customerPostCode;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}

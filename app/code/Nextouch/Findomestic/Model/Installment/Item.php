<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Installment;

use Magento\Quote\Api\Data\CartItemInterface;
use Nextouch\Findomestic\Api\Data\ArrayableInterface;

class Item implements ArrayableInterface
{
    private ?float $amount;
    private ?string $description;

    public function __construct(?float $amount = 0.0, ?string $description = null)
    {
        $this->amount = $amount;
        $this->description = $description;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public static function fromDomain(CartItemInterface $quoteItem): self
    {
        return new self(
            (float) $quoteItem->getPrice(),
            $quoteItem->getName()
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['amount']) ? (float) $data['amount'] : null,
            isset($data['description']) ? (string) $data['description'] : null
        );
    }

    public function toArray(): array
    {
        $arr = [
            'amount' => $this->getAmount(),
            'description' => $this->getDescription(),
        ];

        return array_filter($arr);
    }
}

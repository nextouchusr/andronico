<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Nextouch\Catalog\Api\Data\ProductInterface;

class Product extends \Magento\Catalog\Model\Product implements ProductInterface
{
    public function getAlternativeCode(): string
    {
        return (string) $this->getData(self::ALTERNATIVE_CODE);
    }

    public function setAlternativeCode(string $alternativeCode): self
    {
        $this->setData(self::ALTERNATIVE_CODE, $alternativeCode);

        return $this;
    }

    public function getBrand(): int
    {
        return (int) $this->getData(self::BRAND);
    }

    public function setBrand(int $brand): self
    {
        $this->setData(self::BRAND, $brand);

        return $this;
    }
}

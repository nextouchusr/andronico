<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Magento\Framework\Exception\LocalizedException;
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

    public function getBrandDescription(): string
    {
        try {
            $brandId = $this->getBrand();

            return $this->_resource
                ->getAttribute(self::BRAND)
                ->getSource()
                ->getOptionText($brandId);
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function getFastEstType(): int
    {
        return (int) $this->getData(self::FAST_EST_TYPE);
    }

    public function setFastEstType(int $fastEstType): self
    {
        $this->setData(self::FAST_EST_TYPE, $fastEstType);

        return $this;
    }

    public function getSelectableCouriers(): array
    {
        return (array) $this->getData(self::SELECTABLE_COURIERS);
    }

    public function setSelectableCouriers(array $selectableCouriers): self
    {
        $this->setData(self::SELECTABLE_COURIERS, $selectableCouriers);

        return $this;
    }
}

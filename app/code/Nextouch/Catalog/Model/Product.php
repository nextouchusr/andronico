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

    public function getEcoChoice(): string
    {
        return (string) $this->getData(self::ECO_CHOICE);
    }

    public function setEcoChoice(string $ecoChoice): self
    {
        $this->setData(self::ECO_CHOICE, $ecoChoice);

        return $this;
    }

    public function getEcoChoiceLevel(): int
    {
        return (int) $this->getData(self::ECO_CHOICE_LEVEL);
    }

    public function setEcoChoiceLevel(int $ecoChoiceLevel): self
    {
        $this->setData(self::ECO_CHOICE_LEVEL, $ecoChoiceLevel);

        return $this;
    }

    public function getEaseOfUse(): string
    {
        return (string) $this->getData(self::EASE_OF_USE);
    }

    public function setEaseOfUse(string $easeOfUse): self
    {
        $this->setData(self::EASE_OF_USE, $easeOfUse);

        return $this;
    }

    public function isRecommended(): bool
    {
        return (bool) $this->getData(self::IS_RECOMMENDED);
    }

    public function setIsRecommended(bool $isRecommended): self
    {
        $this->setData(self::IS_RECOMMENDED, $isRecommended);

        return $this;
    }
}

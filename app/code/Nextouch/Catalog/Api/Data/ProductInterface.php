<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api\Data;

/**
 * @api
 */
interface ProductInterface extends \Magento\Catalog\Api\Data\ProductInterface
{
    // Attributes
    public const ALTERNATIVE_CODE = 'alternative_code';
    public const BRAND = 'brand';
    public const FAST_EST_TYPE = 'fast_est_type';
    public const SELECTABLE_CARRIER = 'selectable_carrier';
    public const ECO_CHOICE = 'eco_choice';
    public const ECO_CHOICE_LEVEL = 'eco_choice_level';
    public const EASE_OF_USE = 'ease_of_use';
    public const IS_RECOMMENDED = 'is_recommended';
    public const IS_PICKUPABLE = 'is_pickupable';
    public const IS_RETURNABLE = 'is_returnable';
    public const IS_RETURNABLE_IN_STORE = 'is_returnable_in_store';
    public const STREET_LINE_DELIVERY_PRICE = 'street_line_delivery_price';
    public const FLOOR_DELIVERY_PRICE = 'floor_delivery_price';

    public const DEFAULT_FAST_EST_TYPE = 20; // Altro

    /**
     * @return string
     */
    public function getAlternativeCode(): string;

    /**
     * @param string $alternativeCode
     * @return ProductInterface
     */
    public function setAlternativeCode(string $alternativeCode): self;

    /**
     * @return int
     */
    public function getBrand(): int;

    /**
     * @param int $brand
     * @return ProductInterface
     */
    public function setBrand(int $brand): self;

    /**
     * @return string
     */
    public function getBrandDescription(): string;

    /**
     * @return int
     */
    public function getFastEstType(): int;

    /**
     * @param int $fastEstType
     * @return ProductInterface
     */
    public function setFastEstType(int $fastEstType): self;

    /**
     * @return string
     */
    public function getSelectableCarrier(): string;

    /**
     * @param string $selectableCarrier
     * @return ProductInterface
     */
    public function setSelectableCarrier(string $selectableCarrier): self;

    /**
     * @return string
     */
    public function getEcoChoice(): string;

    /**
     * @param string $ecoChoice
     * @return ProductInterface
     */
    public function setEcoChoice(string $ecoChoice): self;

    /**
     * @return int
     */
    public function getEcoChoiceLevel(): int;

    /**
     * @param int $ecoChoiceLevel
     * @return ProductInterface
     */
    public function setEcoChoiceLevel(int $ecoChoiceLevel): self;

    /**
     * @return string
     */
    public function getEaseOfUse(): string;

    /**
     * @param string $easeOfUse
     * @return ProductInterface
     */
    public function setEaseOfUse(string $easeOfUse): self;

    /**
     * @return bool
     */
    public function isRecommended(): bool;

    /**
     * @param bool $isRecommended
     * @return ProductInterface
     */
    public function setIsRecommended(bool $isRecommended): self;

    /**
     * @return bool
     */
    public function isPickupable(): bool;

    /**
     * @param bool $isPickupable
     * @return ProductInterface
     */
    public function setIsPickupable(bool $isPickupable): self;

    /**
     * @return bool
     */
    public function isReturnableInStore(): bool;

    /**
     * @param bool $isReturnableInStore
     * @return ProductInterface
     */
    public function setIsReturnableInStore(bool $isReturnableInStore): self;

    /**
     * @return float
     */
    public function getStreetLineDeliveryPrice(): float;

    /**
     * @param float $streetLineDeliveryPrice
     * @return ProductInterface
     */
    public function setStreetLineDeliveryPrice(float $streetLineDeliveryPrice): self;

    /**
     * @return float
     */
    public function getFloorDeliveryPrice(): float;

    /**
     * @param float $floorDeliveryPrice
     * @return ProductInterface
     */
    public function setFloorDeliveryPrice(float $floorDeliveryPrice): self;
}

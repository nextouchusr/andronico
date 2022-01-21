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
    public const SELECTABLE_COURIERS = 'selectable_couriers';

    // Customizable Options
    public const SERVICES_OPTION = 'services';

    // Service Option Values
    public const PRODUCT_INSTALL_SERVICE = 'product_install';
    public const USED_PICK_SERVICE = 'used_pick';
    public const ASSEMBLY_SERVICE = 'assembly';
    public const DISASSEMBLY_SERVICE = 'disassembly';
    public const AT_FLOOR_SERVICE = 'at_floor';
    public const UNPACK_SERVICE = 'unpack';
    public const DELIVERY_BY_APPOINTMENT_SERVICE = 'delivery_by_appointment';
    public const PRODUCT_PICKUP_SERVICE = 'product_pickup';
    public const PRODUCT_DELIVERY_SERVICE = 'product_delivery';
    public const NOTICE_CALL_SERVICE = 'notice_call';

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
     * @return string[]
     */
    public function getSelectableCouriers(): array;

    /**
     * @param string[] $selectableCouriers
     * @return ProductInterface
     */
    public function setSelectableCouriers(array $selectableCouriers): self;
}

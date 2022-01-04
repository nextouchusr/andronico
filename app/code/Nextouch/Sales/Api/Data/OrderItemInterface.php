<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

use Nextouch\Catalog\Api\Data\ProductInterface;

/**
 * @api
 */
interface OrderItemInterface extends \Magento\Sales\Api\Data\OrderItemInterface
{
    public const PRODUCT = 'product';
    public const SELECTED_OPTIONS = 'options';

    // Product Service Options
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

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface;

    /**
     * @return array
     */
    public function getSelectedOptions(): array;

    /**
     * @return bool
     */
    public function hasProductInstallService(): bool;

    /**
     * @return bool
     */
    public function hasUsedPickService(): bool;

    /**
     * @return bool
     */
    public function hasAssemblyService(): bool;

    /**
     * @return bool
     */
    public function hasDisassemblyService(): bool;

    /**
     * @return bool
     */
    public function hasAtFloorService(): bool;

    /**
     * @return bool
     */
    public function hasUnpackService(): bool;

    /**
     * @return bool
     */
    public function hasDeliveryByAppointmentService(): bool;

    /**
     * @return bool
     */
    public function hasProductPickupService(): bool;

    /**
     * @return bool
     */
    public function hasProductDeliveryService(): bool;

    /**
     * @return bool
     */
    public function hasNoticeCallService(): bool;
}

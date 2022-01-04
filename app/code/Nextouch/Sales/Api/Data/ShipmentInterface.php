<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

/**
 * @api
 */
interface ShipmentInterface extends \Magento\Sales\Api\Data\ShipmentInterface
{
    public const ORDER = 'order';

    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface;

    /**
     * @return OrderAddressInterface
     */
    public function getShippingAddress(): OrderAddressInterface;

    /**
     * @return ShipmentItemInterface[]
     */
    public function getItems(): array;
}

<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

/**
 * @api
 */
interface ShipmentItemInterface extends \Magento\Sales\Api\Data\ShipmentItemInterface
{
    public const SHIPMENT = 'shipment';
    public const ORDER_ITEM = 'order_item';

    /**
     * @return ShipmentInterface|null
     */
    public function getShipment(): ?ShipmentInterface;

    /**
     * @return OrderItemInterface|null
     */
    public function getOrderItem(): ?OrderItemInterface;
}

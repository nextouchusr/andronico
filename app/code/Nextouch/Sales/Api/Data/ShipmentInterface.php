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
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function getOrder(): OrderInterface;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderAddressInterface
     */
    public function getShippingAddress(): OrderAddressInterface;

    /**
     * @return \Nextouch\Sales\Api\Data\ShipmentItemInterface[]
     */
    public function getItems(): array;
}

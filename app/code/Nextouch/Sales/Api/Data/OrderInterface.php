<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

/**
 * @api
 */
interface OrderInterface extends \Magento\Sales\Api\Data\OrderInterface
{
    /**
     * @return OrderAddressInterface
     */
    public function getShippingAddress(): OrderAddressInterface;

    /**
     * @return OrderItemInterface[]
     */
    public function getItems(): array;
}

<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

/**
 * @api
 */
interface OrderInterface extends \Magento\Sales\Api\Data\OrderInterface
{
    /**
     * @return OrderAddressInterface|null
     */
    public function getShippingAddress(): ?OrderAddressInterface;

    /**
     * @return OrderItemInterface[]
     */
    public function getItems(): array;

    /**
     * @param $itemId
     * @return OrderItemInterface|null
     */
    public function getItemById($itemId): ?OrderItemInterface;
}

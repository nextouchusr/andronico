<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

use Amasty\Deliverydate\Api\Data\DeliverydateInterface;

/**
 * @api
 */
interface OrderInterface extends \Magento\Sales\Api\Data\OrderInterface
{
    public const DELIVERY_INFORMATION = 'delivery_information';

    /**
     * @param string $shippingMethod
     * @return bool
     */
    public function isShippedBy(string $shippingMethod): bool;

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

    /**
     * @return DeliverydateInterface
     */
    public function getDeliveryInformation(): DeliverydateInterface;

    /**
     * @return string
     */
    public function getDeliveryDate(): string;

    /**
     * @return string
     */
    public function getDeliveryTime(): string;

    /**
     * @return string
     */
    public function getDeliveryComment(): string;
}

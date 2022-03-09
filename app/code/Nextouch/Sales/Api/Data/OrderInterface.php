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
     * @return \Nextouch\Sales\Api\Data\OrderAddressInterface|null
     */
    public function getShippingAddress(): ?OrderAddressInterface;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderAddressInterface|null
     */
    public function getBillingAddress(): ?OrderAddressInterface;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderItemInterface[]
     */
    public function getItems(): array;

    /**
     * @param $itemId
     * @return \Nextouch\Sales\Api\Data\OrderItemInterface|null
     */
    public function getItemById($itemId): ?OrderItemInterface;

    /**
     * @return \Amasty\Deliverydate\Api\Data\DeliverydateInterface
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

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Model\Response\Delivery\ConfirmParkedDelivery;
use Nextouch\FastEst\Model\Response\Delivery\InsertNewDelivery;
use Nextouch\Sales\Api\Data\ShipmentInterface;

/**
 * @api
 */
interface DeliveryRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function create(ShipmentInterface $shipment): InsertNewDelivery;

    public function confirmParked(ShipmentInterface $shipment): ConfirmParkedDelivery;
}

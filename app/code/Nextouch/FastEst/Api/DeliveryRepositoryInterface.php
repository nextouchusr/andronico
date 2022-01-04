<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Response\Delivery\InsertNewDelivery;
use Nextouch\Sales\Api\Data\ShipmentInterface;

/**
 * @api
 */
interface DeliveryRepositoryInterface
{
    public function create(ShipmentInterface $shipment): InsertNewDelivery;
}

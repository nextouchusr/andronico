<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Model\Response\Delivery\ConfirmParkedDelivery;
use Nextouch\FastEst\Model\Response\Delivery\InsertNewDelivery;
use Nextouch\Sales\Api\Data\OrderInterface;

/**
 * @api
 */
interface DeliveryRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function create(OrderInterface $order): InsertNewDelivery;

    public function confirmParked(OrderInterface $order): ConfirmParkedDelivery;
}

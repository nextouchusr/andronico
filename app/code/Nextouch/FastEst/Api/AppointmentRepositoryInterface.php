<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Collections\Exceptions\InvalidArgumentException;
use Magento\Quote\Api\Data\CartInterface;
use Nextouch\FastEst\Model\Response\Appointment\GetAvailableSlotList;

/**
 * @api
 */
interface AppointmentRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAvailableSlots(CartInterface $cart): GetAvailableSlotList;
}

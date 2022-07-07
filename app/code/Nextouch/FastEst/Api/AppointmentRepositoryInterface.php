<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Appointment\CartInformation;
use Nextouch\FastEst\Model\Response\Appointment\GetAvailableSlotList;

/**
 * @api
 */
interface AppointmentRepositoryInterface
{
    /**
     * @param \Nextouch\FastEst\Model\Appointment\CartInformation $cart
     * @return \Nextouch\FastEst\Model\Response\Appointment\GetAvailableSlotList
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Collections\Exceptions\InvalidArgumentException
     */
    public function getAvailableSlots(CartInformation $cart): GetAvailableSlotList;
}

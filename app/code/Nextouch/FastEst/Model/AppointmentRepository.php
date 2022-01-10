<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Magento\Quote\Api\Data\CartInterface;
use Nextouch\FastEst\Api\AppointmentRepositoryInterface;
use Nextouch\FastEst\Model\Appointment\SlotRequest;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Request\Appointment\GetAvailableSlotList as GetAvailableSlotListRequest;
use Nextouch\FastEst\Model\Response\Appointment\GetAvailableSlotList as GetAvailableSlotListResponse;

class AppointmentRepository extends AbstractBaseRepository implements AppointmentRepositoryInterface
{
    public function getAvailableSlots(CartInterface $cart): GetAvailableSlotListResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $slotRequest = SlotRequest::fromDomain($cart);
        $request = new GetAvailableSlotListRequest($login, $slotRequest);

        $result = $this->client->call('get_available_slots', $request->asObject());

        return GetAvailableSlotListResponse::fromObject($result);
    }
}

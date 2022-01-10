<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Request\Appointment;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Model\Appointment\SlotRequest;
use Nextouch\FastEst\Model\Common\Login;

class GetAvailableSlotList implements InputInterface
{
    private Login $login;
    private SlotRequest $slotRequest;

    public function __construct(Login $login, SlotRequest $slotRequest)
    {
        $this->login = $login;
        $this->slotRequest = $slotRequest;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getSlotRequest(): SlotRequest
    {
        return $this->slotRequest;
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->login = $this->getLogin()->asObject();
        $object->slot_request = $this->getSlotRequest()->asObject();

        return $object;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Request\Delivery;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Model\Common\Login;

class ConfirmParkedDelivery implements InputInterface
{
    private Login $login;
    private string $storeOrder;

    public function __construct(Login $login, string $storeOrder)
    {
        $this->login = $login;
        $this->storeOrder = $storeOrder;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getStoreOrder(): string
    {
        return $this->storeOrder;
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->login = $this->getLogin()->asObject();
        $object->store_order = $this->getStoreOrder();

        return $object;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Request\Directory;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Model\Common\Login;

class GetDirectoryList implements InputInterface
{
    private Login $login;

    public function __construct(Login $login)
    {
        $this->login = $login;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->login = $this->getLogin()->asObject();

        return $object;
    }
}

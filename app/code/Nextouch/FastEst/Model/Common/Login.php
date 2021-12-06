<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Common;

use Nextouch\FastEst\Api\Data\InputInterface;

class Login implements InputInterface
{
    private string $username;
    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->login = new \stdClass();
        $object->login->username = $this->getUsername();
        $object->login->password = $this->getPassword();

        return $object;
    }
}

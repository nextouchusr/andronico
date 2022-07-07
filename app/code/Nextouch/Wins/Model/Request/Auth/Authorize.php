<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Request\Auth;

use Nextouch\Wins\Api\Data\InputInterface;

class Authorize implements InputInterface
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

    public function toArray(): array
    {
        return [
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
        ];
    }
}

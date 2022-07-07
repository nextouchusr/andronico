<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Auth;

use Nextouch\Wins\Api\Data\ArrayableInterface;

class LoginInfo implements ArrayableInterface
{
    private string $user;
    private string $password;

    private function __construct(string $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['user'],
            (string) $data['password']
        );
    }

    public function toArray(): array
    {
        return [
            'user' => $this->getUser(),
            'password' => $this->getPassword(),
        ];
    }
}

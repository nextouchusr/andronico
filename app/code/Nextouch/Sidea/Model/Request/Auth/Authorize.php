<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Model\Request\Auth;

use Nextouch\Sidea\Api\Data\InputInterface;

class Authorize implements InputInterface
{
    private string $grantType;
    private string $clientId;
    private string $clientSecret;
    private string $accountId;

    public function __construct(
        string $grantType,
        string $clientId,
        string $clientSecret,
        string $accountId
    ) {
        $this->grantType = $grantType;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accountId = $accountId;
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function toArray(): array
    {
        return [
            'grant_type' => $this->getGrantType(),
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'account_id' => $this->getAccountId(),
        ];
    }
}

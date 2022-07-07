<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Request\Installment;

use Nextouch\Findomestic\Api\Data\InputInterface;

class Refresh implements InputInterface
{
    private string $sessionExpiry;
    private string $applicationId;
    private string $token;

    public function __construct(
        string $sessionExpiry,
        string $applicationId,
        string $token
    ) {
        $this->sessionExpiry = $sessionExpiry;
        $this->applicationId = $applicationId;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getSessionExpiry(): string
    {
        return $this->sessionExpiry;
    }

    /**
     * @return string
     */
    public function getApplicationId(): string
    {
        return $this->applicationId;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    public function toArray(): array
    {
        return [
            'sessionExpiry' => $this->getSessionExpiry(),
            'applicationId' => $this->getApplicationId(),
            'token' => $this->getToken(),
        ];
    }
}

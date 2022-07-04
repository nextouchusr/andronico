<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Model\Request\Event;

use Nextouch\Sidea\Api\Data\InputInterface;
use Nextouch\Sidea\Model\Event\AbandonedCart\Data;

class AbandonedCart implements InputInterface
{
    private string $accessToken;
    private string $contactKey;
    private string $eventDefinitionKey;
    private Data $data;

    public function __construct(
        string $accessToken,
        string $contactKey,
        string $eventDefinitionKey,
        Data $data
    ) {
        $this->accessToken = $accessToken;
        $this->contactKey = $contactKey;
        $this->eventDefinitionKey = $eventDefinitionKey;
        $this->data = $data;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getContactKey(): string
    {
        return $this->contactKey;
    }

    public function getEventDefinitionKey(): string
    {
        return $this->eventDefinitionKey;
    }

    public function getData(): Data
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'ContactKey' => $this->getContactKey(),
            'EventDefinitionKey' => $this->getEventDefinitionKey(),
            'Data' => $this->getData()->toArray(),
        ];
    }
}

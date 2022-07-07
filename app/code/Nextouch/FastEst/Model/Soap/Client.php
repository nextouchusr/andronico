<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Soap;

use Laminas\Server\ClientInterface;
use Laminas\Soap\Client as BaseClient;
use Nextouch\FastEst\Helper\FastEstConfig;

class Client implements ClientInterface
{
    private BaseClient $client;

    public function __construct(
        FastEstConfig $config,
        BaseClient $client,
        string $scopeCode = null
    ) {
        $wsdl = $config->getWsdl($scopeCode);
        $this->client = $client->setWSDL($wsdl);
    }

    public function call($method, $params = [])
    {
        return $this->client->call($method, $params);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Magento\Framework\ObjectManagerInterface;
use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Helper\FastEstConfig;
use Nextouch\FastEst\Model\Soap\Client;
use Psr\Log\LoggerInterface;

abstract class AbstractBaseRepository
{
    protected FastEstConfig $config;
    protected Client $client;
    protected ?string $scopeCode;
    private LoggerInterface $logger;

    public function __construct(
        FastEstConfig $config,
        ObjectManagerInterface $objectManager,
        LoggerInterface $logger,
        string $scopeCode = null
    ) {
        $this->config = $config;
        $this->client = $objectManager->create(Client::class, ['scopeCode' => $scopeCode]);
        $this->logger = $logger;
        $this->scopeCode = $scopeCode;
    }

    protected function doRequest(string $method, InputInterface $data): \stdClass
    {
        $result = $this->client->call($method, $data->asObject());

        if (!$result->ok) {
            $this->logger->error(__('Error during FastEst SOAP request %1 - %2', $method, $result->error_descr));
        }

        return $result;
    }
}

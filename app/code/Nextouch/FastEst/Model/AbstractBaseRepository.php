<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Magento\Framework\ObjectManagerInterface;
use Nextouch\FastEst\Helper\FastEstConfig;
use Nextouch\FastEst\Model\Soap\Client;

abstract class AbstractBaseRepository
{
    protected FastEstConfig $config;
    protected Client $client;
    protected ?string $scopeCode;

    public function __construct(
        FastEstConfig $config,
        ObjectManagerInterface $objectManager,
        string $scopeCode = null
    ) {
        $this->config = $config;
        $this->client = $objectManager->create(Client::class, ['scopeCode' => $scopeCode]);
        $this->scopeCode = $scopeCode;
    }
}

<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Gateway\Http\Client;

use Axepta\Paymentservice\Helper\Data;
use Magento\Framework\App\ObjectManager;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;

abstract class AbstractTransaction implements ClientInterface
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var Data
     */
    protected $helper;

    protected $objectManager;

    protected $adapterFactory;

    /**
     * @param Logger $logger
     */

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->objectManager = ObjectManager::getInstance();
        $this->adapterFactory = $this->objectManager->get('Axepta\Paymentservice\Model\Adapter\AxeptaAdapterFactory');
    }

    /**
     * Places request to gateway. Returns result as ENV array
     *
     * @param TransferInterface $transferObject
     * @return array
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $data = $transferObject->getBody();
        $log = [
            'request' => $data,
            'client' => static::class
        ];

        $response = [];
        $response = $this->process($data);

        return $response;
    }

    /** Process http request
     * @param array $data
     * @return mixed
     */
    abstract protected function process(array $data);
}

<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace Axepta\Paymentservice\Model\Adapter;

use Magento\Framework\ObjectManagerInterface;

/**
 * This factory is preferable to use for Braintree adapter instance creation.
 */
class AxeptaAdapterFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Creates instance of Braintree Adapter.
     *
     * @param int $storeId if null is provided as an argument, then current scope will be resolved
     * by \Magento\Framework\App\Config\ScopeCodeResolver (useful for most cases) but for adminhtml area the store
     * should be provided as the argument for correct config settings loading.
     * @return AxeptaAdapter
     */
    public function create($storeId = null)
    {
        return $this->objectManager->create(
            AxeptaAdapter::class,
            [
                'merchantId' => null,
                'publicKey' => null,
                'privateKey' => null,
                'environment' => 'test'
            ]
        );
    }
}

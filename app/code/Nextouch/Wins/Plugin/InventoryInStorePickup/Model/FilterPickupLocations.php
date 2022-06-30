<?php
declare(strict_types=1);

namespace Nextouch\Wins\Plugin\InventoryInStorePickup\Model;

use Magento\InventoryInStorePickupApi\Api\Data\SearchRequestInterface;
use Magento\InventoryInStorePickupApi\Api\Data\SearchRequestInterfaceFactory;
use Magento\InventoryInStorePickupApi\Api\GetPickupLocationsInterface;

class FilterPickupLocations
{
    private SearchRequestInterfaceFactory $searchRequestFactory;

    public function __construct(SearchRequestInterfaceFactory $searchRequestFactory)
    {
        $this->searchRequestFactory = $searchRequestFactory;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeExecute(GetPickupLocationsInterface $subject, SearchRequestInterface $searchRequest): array
    {
        $searchRequest = $this->searchRequestFactory->create([
            'scopeCode' => $searchRequest->getScopeCode(),
            'scopeType' => $searchRequest->getScopeType(),
            'searchRequestExtension' => $searchRequest->getExtensionAttributes(),
        ]);

        return [$searchRequest];
    }
}

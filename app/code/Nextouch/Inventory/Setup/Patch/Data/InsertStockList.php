<?php
declare(strict_types=1);

namespace Nextouch\Inventory\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\InventoryApi\Api\Data\StockInterfaceFactory;
use Magento\InventoryApi\Api\Data\StockSourceLinkInterfaceFactory;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\InventoryApi\Api\StockSourceLinksSaveInterface;
use Magento\InventoryCatalogApi\Api\DefaultStockProviderInterface;
use Magento\InventorySalesApi\Model\GetAssignedSalesChannelsForStockInterface;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;

class InsertStockList implements DataPatchInterface
{
    private DefaultStockProviderInterface $defaultStockProvider;
    private GetAssignedSalesChannelsForStockInterface $getAssignedSalesChannelsForStock;
    private StockInterfaceFactory $stockFactory;
    private StockRepositoryInterface $stockRepository;
    private StockSourceLinkInterfaceFactory $stockSourceLinkFactory;
    private StockSourceLinksSaveInterface $stockSourceLinksSave;

    public function __construct(
        DefaultStockProviderInterface $defaultStockProvider,
        GetAssignedSalesChannelsForStockInterface $getAssignedSalesChannelsForStock,
        StockInterfaceFactory $stockFactory,
        StockRepositoryInterface $stockRepository,
        StockSourceLinkInterfaceFactory $stockSourceLinkFactory,
        StockSourceLinksSaveInterface $stockSourceLinksSave
    ) {
        $this->defaultStockProvider = $defaultStockProvider;
        $this->getAssignedSalesChannelsForStock = $getAssignedSalesChannelsForStock;
        $this->stockFactory = $stockFactory;
        $this->stockRepository = $stockRepository;
        $this->stockSourceLinkFactory = $stockSourceLinkFactory;
        $this->stockSourceLinksSave = $stockSourceLinksSave;
    }

    public static function getDependencies(): array
    {
        return [
            InsertSourceList::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }

    /**
     * @throws LocalizedException
     */
    public function apply(): self
    {
        $defaultStockId = $this->defaultStockProvider->getId();
        $salesChannels = $this->getAssignedSalesChannelsForStock->execute($defaultStockId);

        each(function (array $data) use ($salesChannels) {
            $stock = $this->stockFactory->create();
            $stock->setName($data['name']);
            $stock->getExtensionAttributes()->setSalesChannels($salesChannels);
            $stockId = $this->stockRepository->save($stock);

            $stockSourceLinks = map(function (array $source) use ($stockId) {
                $stockSourceLink = $this->stockSourceLinkFactory->create();
                $stockSourceLink->setStockId($stockId);
                $stockSourceLink->setSourceCode($source['code']);
                $stockSourceLink->setPriority($source['priority']);

                return $stockSourceLink;
            }, $data['sources']);

            $this->stockSourceLinksSave->execute($stockSourceLinks);
        }, $this->getStockList());

        return $this;
    }

    private function getStockList(): array
    {
        return [
            [
                'name' => 'Nextouch',
                'sources' => [
                    ['code' => 'ecommerce', 'priority' => 1],
                    ['code' => 'MB1', 'priority' => 2],
                ],
            ],
        ];
    }
}

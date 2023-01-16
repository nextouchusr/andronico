<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Cron;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Nextouch\ImportExport\Model\WinsEntityDataOperationCombiner;

class WinsCatalogDataImportJob
{
    private WinsEntityDataOperationCombiner $entityDataOperationCombiner;
    private CollectionFactory $collectionFactory;

    public function __construct(
        WinsEntityDataOperationCombiner $entityDataOperationCombiner,
        CollectionFactory $collectionFactory
    ) {
        $this->entityDataOperationCombiner = $entityDataOperationCombiner;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute(): void
    {
        $this->entityDataOperationCombiner->run();

        $this->disableProductsWithNoPrice();
    }

    private function disableProductsWithNoPrice(): void
    {
        $this->collectionFactory
            ->create()
            ->addFieldToFilter(ProductInterface::PRICE, ['lteq' => 0])
            ->setDataToAll(ProductInterface::STATUS, Status::STATUS_DISABLED)
            ->save();
    }
}

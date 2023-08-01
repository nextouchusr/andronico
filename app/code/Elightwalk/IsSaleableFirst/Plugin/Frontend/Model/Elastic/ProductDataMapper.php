<?php

namespace Elightwalk\IsSaleableFirst\Plugin\Frontend\Model\Elastic;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Elasticsearch\Model\Adapter\BatchDataMapper\ProductDataMapper as ProductDataMapperAlias;

class ProductDataMapper
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * @var string
     */
    protected $cataloginventoryStockStatusTable;

    protected $catalogProductEntityTable;

    protected $inventorySourceItemTable;

    /**
     * __construct
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Map index data for using in search engine metadata
     *
     * @param ProductDataMapperAlias $subject
     * @param array $result
     * @param array $documentData
     * @param int $storeId
     * @param array $context
     * @return array
     */
    public function afterMap($subject, $result, array $documentData, $storeId, array $context = [])
    {
        $this->connection = $this->resourceConnection->getConnection();
        $this->cataloginventoryStockStatusTable = $this->resourceConnection->getTableName('cataloginventory_stock_status');
        $this->catalogProductEntityTable = $this->resourceConnection->getTableName('catalog_product_entity');
        $this->inventorySourceItemTable = $this->resourceConnection->getTableName('inventory_source_item');

        foreach ($result as $productId => $indexData) {
            $additionalData = $this->getAdditionalData($productId, $storeId);
            $result[$productId] = array_merge_recursive(
                $result[$productId],
                $additionalData
            );
        }

        return $result;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return array
     */
    protected function getAdditionalData($productId, $storeId)
    {
        $additionalData     = [];
        $productStockQty = $this->getProductStockStatus($productId, $storeId);

        $additionalData['available_qty']  = $productStockQty;


        if($productId == 37042) {
            file_put_contents(BP . '/var/log/additionalData.log', print_r($additionalData, true)."\n", FILE_APPEND);			

        }
       
        return $additionalData;
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return string
     */
    protected function getProductStockStatus($productId, $storeId)
    {
        /*$productStockQty = $this->connection->fetchOne(
            "SELECT qty FROM " . $this->cataloginventoryStockStatusTable . " WHERE product_id = " . $productId
        );*/

        $productSku = $this->connection->fetchOne(
            "SELECT sku FROM " . $this->catalogProductEntityTable . " WHERE entity_id = " . $productId
        );

        $productStockQty = $this->connection->fetchOne(
            "SELECT sum(quantity) FROM " . $this->inventorySourceItemTable . " WHERE sku = '" . $productSku."'"
        );


        return $productStockQty;
    }
}

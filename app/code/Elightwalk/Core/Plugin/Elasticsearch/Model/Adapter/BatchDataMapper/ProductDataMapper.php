<?php

namespace Elightwalk\Core\Plugin\Elasticsearch\Model\Adapter\BatchDataMapper;

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
     * AfterMap
     *
     * @param ProductDataMapperAlias $subject
     * @param array $result
     * @param array $documentData
     * @param int $storeId
     * @param array $context
     * @return array
     */
    public function afterMap(
        ProductDataMapperAlias $subject,
        $result,
        array $documentData,
        $storeId,
        array $context = []
    ) {
        $this->connection = $this->resourceConnection->getConnection();
        $this->cataloginventoryStockStatusTable = $this->resourceConnection->getTableName('cataloginventory_stock_status');

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
     * GetAdditionalData
     *
     * @param int $productId
     * @param int $storeId
     * @return array
     */
    protected function getAdditionalData($productId, $storeId)
    {
        $additionalData     = [];
        $productStockStatus = $this->getProductStockStatus($productId, $storeId);

        $additionalData['is_salable']  = $productStockStatus;

        return $additionalData;
    }

    /**
     * GetProductStockStatus
     *
     * @param int $productId
     * @param int $storeId
     * @return string
     */
    protected function getProductStockStatus($productId, $storeId)
    {
        $productStockStatus = $this->connection->fetchOne(
            "SELECT stock_status FROM " . $this->cataloginventoryStockStatusTable . " WHERE product_id = " . $productId
        );

        return $productStockStatus;
    }
}

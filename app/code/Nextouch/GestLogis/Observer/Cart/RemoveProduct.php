<?php

namespace Nextouch\GestLogis\Observer\Cart;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Magento\Framework\App\ResourceConnection;

class RemoveProduct implements ObserverInterface
{


    /**
     * @var CookieManagerInterface
     */
    private $_cookieManager;

    /**
     * @var ResourceConnection
     */
    private $_resourceConnection;

    /**
     * @var DataHelper
     */
    private $_dataHelper;

    /**
     * __construct
     *
     * @param CookieManagerInterface $cookieManager
     * @param DataHelper $dataHelper
     * @param ResourceConnection $resource
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        DataHelper $dataHelper,
        ResourceConnection $resource
    ) {
        $this->_cookieManager = $cookieManager;
        $this->_dataHelper = $dataHelper;
        $this->_resourceConnection = $resource;
    }

    public function execute(Observer $observer)
    {

        $quoteItem = $observer->getEvent()->getQuoteItem();

        $connection = $this->_resourceConnection->getConnection();
        $tableName = $connection->getTableName('nextouch_gestlogis_quote_shipping_service');

        $deleteSql = "DELETE FROM " . $tableName . " WHERE item_id = " . $quoteItem->getId();
        file_put_contents(BP . '/var/log/remove_item.log', print_r($deleteSql, true)."\n", FILE_APPEND);			

        $connection->query($deleteSql);

        $quote = $quoteItem->getQuote();

        $allitems = $quote->getAllItems();

        $itemIds = [];

        foreach ($allitems as $item) {

            $selectSql = "Select * FROM " . $tableName . " WHERE item_id = " . $item->getId();
            file_put_contents(BP . '/var/log/remove_item.log', print_r($selectSql, true)."\n", FILE_APPEND);			

            if ($connection->fetchOne($selectSql)) {
                $itemIds[] = $item->getId();
            }
        }
        file_put_contents(BP . '/var/log/remove_item.log', print_r($itemIds, true)."\n", FILE_APPEND);			


        if (count($itemIds) == 0) {
            $this->_dataHelper->unsetSessionValue(DataHelper::GESTLOGIS_SESSION_POSTCODE);
        }

        return $this;

    }
}
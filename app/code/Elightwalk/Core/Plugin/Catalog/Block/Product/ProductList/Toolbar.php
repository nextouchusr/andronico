<?php

namespace Elightwalk\Core\Plugin\Catalog\Block\Product\ProductList;

use Magento\Catalog\Model\Product\ProductList\Toolbar as ToolbarModel;
use Magento\Store\Model\StoreManagerInterface as StoreManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Block\Product\ProductList\Toolbar as ToolbarBlock;
use Magento\Store\Model\ScopeInterface;

class Toolbar
{
    /**
     * @var ToolbarModel
     */
    protected $_toolbarModel;

    /**
     * @var StoreManager
     */
    protected $_storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var bool - flag for already filtered collection
     */
    protected $filtered = false;

    /**
     * __construct
     *
     * @param ToolbarModel $toolbarModel
     * @param StoreManager $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param Registry $registry
     */
    public function __construct(
        ToolbarModel $toolbarModel,
        StoreManager $storeManager,
        ScopeConfigInterface $scopeConfig,
        Registry $registry
    ) {
        $this->_toolbarModel = $toolbarModel;
        $this->_storeManager = $storeManager;
        $this->_scopeConfig  = $scopeConfig;
        $this->_registry     = $registry;
    }

    /**
     * AfterSetCollection
     *
     * @param ToolbarBlock $subject
     * @param array $result
     * @return void
     */
    public function afterSetCollection(
        ToolbarBlock $subject,
        $result
    ) {
        $collection = $subject->getCollection();
        if ($_SERVER["REMOTE_ADDR"] == "103.209.67.172") {
            try {
                // $collection->getSelect()->join(
                //     [
                //         'stock_index' => 'cataloginventory_stock_status',
                //     ],
                //     'e.entity_id = stock_index.product_id',
                //     ['stock_status']
                // );
                //$collection->setOrder('is_salable', 'DESC');
                //$collection->getSelect()->order("is_salable DESC");
                //$collection->getSelect()->order("stock_status DESC");
                //file_put_contents(BP . '/var/log/customlog.log', print_r($collection->getSelect()->__toString(), true) . "\n", FILE_APPEND);
            } catch (\Exception $e) {
                //file_put_contents(BP . '/var/log/customlog.log', print_r($e->getMessage(), true) . "\n", FILE_APPEND);
            } catch (\Exception $error) {
                //file_put_contents(BP . '/var/log/customlog.log', print_r($error->getMessage(), true) . "\n", FILE_APPEND);
            }
        }
        // if ($subject->getCurrentOrder()) {
        //     if ($this->_getRealCurrentOrder($subject) == 'is_salable') {
        //         if (!$this->filtered) {
        //             $collection->setOrder('is_salable', $this->_getRealCurrentDirection($subject));
        //         }

        //         $this->filtered = true;
        //     }
        // }
    }

    /**
     * AroundSetDefaultOrder
     *
     * @param ToolbarBlock $subject
     * @param \Closure $proceed
     * @param mixed $field
     * @return mixed
     */
    // public function aroundSetDefaultOrder(
    //     ToolbarBlock $subject,
    //     \Closure $proceed,
    //     $field
    // ) {
    //     $field =  'is_salable~DESC';
    //     return $proceed($field);
    // }

    /**
     * _getRealCurrentDirection
     *
     * @param object $subject
     * @return bool|string
     */
    // private function _getRealCurrentDirection($subject)
    // {
    //     if ($direction = $this->_toolbarModel->getDirection()) {
    //         return $direction;
    //     }

    //     switch ($this->_getRealCurrentOrder($subject)) {
    //         case 'is_salable':
    //             $direction = 'desc';
    //             break;
    //         default:
    //             $direction = $subject->getCurrentDirection();
    //             break;
    //     }

    //     return $direction;
    // }

    /**
     * _getRealCurrentOrder
     *
     * @param object $subject
     * @return mixed
     */
    // private function _getRealCurrentOrder($subject)
    // {
    //     $orders       = $subject->getAvailableOrders();
    //     $defaultOrder = $subject->getOrderField();

    //     if (!isset($orders[$defaultOrder])) {
    //         $defaultOrder = isset($orders['is_salable']) ? 'is_salable' : $this->_getDefaultSortOrder();
    //     }

    //     $order = $this->_toolbarModel->getOrder();
    //     if (!$order || !isset($orders[$order])) {
    //         $order = $defaultOrder;
    //     }
    //     $orderArr = explode('~', $order);

    //     return reset($orderArr);
    // }

    /**
     * _getDefaultSortOrder
     *
     * @return mixed
     */
    // private function _getDefaultSortOrder()
    // {
    //     $currentCategory = $this->_registry->registry('current_category');
    //     if ($currentCategory && $defaultCategorySortBy = $currentCategory->getData('default_sort_by')) {
    //         return $defaultCategorySortBy;
    //     }

    //     return $this->_scopeConfig->getValue('catalog/frontend/default_sort_by', ScopeInterface::SCOPE_STORE, $this->_storeManager->getStore()->getId());
    // }
}

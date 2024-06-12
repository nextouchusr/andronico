<?php

namespace Nextouch\GestLogis\Observer\Quote;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Nextouch\GestLogis\Model\Carrier\Shipping as GestLogisCarrier;

class Merge implements ObserverInterface
{

    /**
     * @var DataHelper
     */
    private $_dataHelper;

    /**
     * __construct
     *
     * @param DataHelper $dataHelper
     */
    public function __construct(
        DataHelper $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        file_put_contents(BP . '/var/log/merge_log.log', print_r('___________________execute_______________________', true) . "\n", FILE_APPEND);

        $guestQuote = $observer->getEvent()->getSource();   //before merge
        $customerQuote = $observer->getEvent()->getQuote(); //after merge

        $guestQuoteItems = $guestQuote->getAllVisibleItems();
        $customerQuoteItems = $customerQuote->getAllVisibleItems();

        $guestQuoteItemIds = [];
        $customerQuoteItemIds = [];
        foreach ($guestQuoteItems as $item) {
            $guestQuoteItemIds[] = $item->getId();
        }
        foreach ($customerQuoteItems as $item) {
            $customerQuoteItemIds[] = $item->getId();
        }
    
        $oldItemProcessed = [];
        foreach ($customerQuoteItems as $item) {
            $productId = $item->getProductId();

            $services = [];
            $_oldItem = null;
            foreach ($guestQuoteItems as $olditem) {

                if ($olditem->getProductId() == $productId && !in_array($olditem->getId(), $oldItemProcessed) && $olditem->getQuoteId() != $item->getQuoteId()) {

                    $newservices = $this->_dataHelper->getQuoteShippingServiceCollectionObj()->addFieldToFilter('item_id', $item->getId());
                    

                    if (count($newservices) == 0) {
                        $services = $this->_dataHelper->getQuoteShippingServiceCollectionObj()->addFieldToFilter('item_id', $olditem->getId());
                        $oldItemProcessed[] = $olditem->getId();
                        $_oldItem = $olditem;
                        break;
                    }
                }
            }
            
            if ($_oldItem) {
                if (count($services)) {
                    $product = $this->_dataHelper->getProductById($productId);
                    $this->_dataHelper->saveQuoteShippingServiceAll($item->getId(), $services, $product);
                }
            }

        }
        
    }


    /*public function execute(\Magento\Framework\Event\Observer $observer)
    {
        file_put_contents(BP . '/var/log/merge_log.log', print_r('execute', true) . "\n", FILE_APPEND);

        $guestQuote = $observer->getEvent()->getSource();   //before merge
        $customerQuote = $observer->getEvent()->getQuote(); //after merge

        $guestQuoteItems = $guestQuote->getAllVisibleItems();

        foreach ($guestQuoteItems as $item) {

            $customerQuoteItem = false;
            foreach ($customerQuote->getAllItems() as $quoteItem) {
                if ($quoteItem->compare($item)) {
                    $customerQuoteItem = $quoteItem;
                    break;
                }
            }

            if ($customerQuoteItem) {
                $productId = $item->getProductId();

                $collection = $this->_dataHelper->getQuoteShippingServiceCollectionObj()->addFieldToFilter('item_id', $item->getId());
                foreach($collection as $call){
                    file_put_contents(BP . '/var/log/merge_log.log', print_r([
                        'entity_id' => $call->getId(),
                        'item_id' => $call->getItemId(),
                        'service_code' => $call->getServiceCode()
                    ], true) . "\n", FILE_APPEND);
                }

                $product = $this->_dataHelper->getProductById($productId);

                if ($customerQuoteItem) {
                    file_put_contents(BP . '/var/log/merge_log.log', print_r([
                        'productId' => $productId,
                        'item_id' => $item->getId(),
                        'customerQuote_item_id' => $customerQuoteItem->getId()
                    ], true) . "\n", FILE_APPEND);


                    $services = $this->_dataHelper->getServicesByItemId($item->getId());
                    file_put_contents(BP . '/var/log/merge_log.log', print_r([
                        'services' => count($services),
                    ], true) . "\n", FILE_APPEND);
                    if ($services) {
                        $this->_dataHelper->saveQuoteShippingServiceAll($customerQuoteItem->getId(), $services, $product);
                    }
                }
            }
          
        }

        file_put_contents(BP . '/var/log/merge_log.log', print_r('execute end', true) . "\n", FILE_APPEND);

    }*/

    private function getCustomerQuoteItem($customerQuote, $productId)
    {
        $customerQuoteItems = $customerQuote->getAllVisibleItems();
        $objitem = null;
        foreach ($customerQuoteItems as $item) {
            if ($item->getProductId() == $productId) {
                $objitem = $item;
            }
        }
        return $objitem;
    }
}
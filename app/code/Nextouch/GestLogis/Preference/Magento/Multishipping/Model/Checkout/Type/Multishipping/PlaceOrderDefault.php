<?php

declare(strict_types=1);

namespace Nextouch\GestLogis\Preference\Magento\Multishipping\Model\Checkout\Type\Multishipping;

use Magento\Multishipping\Model\Checkout\Type\Multishipping\PlaceOrderDefault as MagentoPlaceOrderDefault;
use Magento\Multishipping\Model\Checkout\Type\Multishipping\PlaceOrderInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Nextouch\GestLogis\Helper\Data as DataHelper;

class PlaceOrderDefault extends MagentoPlaceOrderDefault implements PlaceOrderInterface
{
    /**
     * @var OrderManagementInterface
     */
    private $orderManagement;

    /**
     * @var DataHelper
     */
    private $_dataHelper;

    /**
     * __construct
     *
     * @param OrderManagementInterface $orderManagement
     * @param DataHelper $dataHelper
     */
    public function __construct(
        OrderManagementInterface $orderManagement,
        DataHelper $dataHelper
    ) {
        parent::__construct($orderManagement);
        $this->orderManagement = $orderManagement;
        $this->_dataHelper     = $dataHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function place(array $orderList): array
    {
        $errorList      = [];
        $shippingActive = $this->_dataHelper->isShippingActive();
        foreach ($orderList as $order) {
            try {
                $this->orderManagement->place($order);

                if ($shippingActive && $order) {
                    $this->executeOrderShippingService($order);
                }
            } catch (\Exception $e) {
                $incrementId = $order->getIncrementId();
                $errorList[$incrementId] = $e;
            }
        }

        return $errorList;
    }

    /**
     * ExecuteOrderShippingService
     *
     * @param object $order
     * @return void
     */
    public function executeOrderShippingService($order)
    {
        if ($order) {
            $items = $order->getItems();

            if (count($items)) {
                foreach ($items as $item) {
                    $quoteItemId = (int) $item->getQuoteItemId();
                    $itemId      = (int) $item->getItemId();

                    $this->_dataHelper->saveOrderShippingService($itemId, $quoteItemId);
                }
            }
        }
    }
}

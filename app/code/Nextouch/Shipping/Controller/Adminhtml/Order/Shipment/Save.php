<?php
declare(strict_types=1);

namespace Nextouch\Shipping\Controller\Adminhtml\Order\Shipment;

class Save extends \Magento\Shipping\Controller\Adminhtml\Order\Shipment\Save
{
    protected function _saveShipment($shipment): self
    {
        $isNew = $shipment->isObjectNew();

        if ($isNew) {
            $this->_eventManager->dispatch('sales_order_ship_before', ['shipment' => $shipment]);
        }

        parent::_saveShipment($shipment);

        if ($isNew) {
            $this->_eventManager->dispatch('sales_order_ship_after', ['shipment' => $shipment]);
        }

        return $this;
    }
}

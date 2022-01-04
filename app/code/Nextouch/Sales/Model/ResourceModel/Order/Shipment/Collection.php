<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\ResourceModel\Order\Shipment;

use Magento\Sales\Model\ResourceModel\Order\Shipment as ShipmentResourceModel;
use Magento\Sales\Model\ResourceModel\Order\Shipment\Collection as ShipmentCollection;
use Nextouch\Sales\Model\Order\Shipment;

class Collection extends ShipmentCollection
{
    protected function _construct(): void
    {
        $this->_init(Shipment::class, ShipmentResourceModel::class);
    }
}

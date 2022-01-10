<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\ShipmentInterface;
use Nextouch\FastEst\Api\DeliveryRepositoryInterface;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Sales\Api\Data\ShipmentInterface as FastEstShipmentInterface;
use Nextouch\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;

class CreateNewDelivery implements ObserverInterface
{
    private DeliveryRepositoryInterface $deliveryRepository;
    private ShipmentCollectionFactory $shipmentCollectionFactory;

    public function __construct(
        DeliveryRepositoryInterface $deliveryRepository,
        ShipmentCollectionFactory $shipmentCollectionFactory
    ) {
        $this->deliveryRepository = $deliveryRepository;
        $this->shipmentCollectionFactory = $shipmentCollectionFactory;
    }

    public function execute(Observer $observer): void
    {
        /** @var ShipmentInterface $shipment */
        $shipment = $observer->getData('shipment');

        /** @var FastEstShipmentInterface $fastEstShipment */
        $fastEstShipment = $this->shipmentCollectionFactory->create()->getItemById($shipment->getEntityId());

        if (!$fastEstShipment->getOrder()->isShippedBy(FastEst::CODE)) {
            return;
        }

        $this->deliveryRepository->create($fastEstShipment);
    }
}

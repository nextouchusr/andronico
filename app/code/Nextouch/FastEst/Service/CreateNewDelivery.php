<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Service;

use Collections\Exceptions\InvalidArgumentException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\ShipmentInterface;
use Nextouch\FastEst\Api\DeliveryRepositoryInterface;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Sales\Api\Data\ShipmentInterface as FastEstShipmentInterface;
use Nextouch\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;

class CreateNewDelivery
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

    /**
     * @throws InvalidArgumentException
     * @throws LocalizedException
     */
    public function create(ShipmentInterface $shipment): void
    {
        /** @var FastEstShipmentInterface $fastEstShipment */
        $fastEstShipment = $this->shipmentCollectionFactory->create()->getItemById($shipment->getEntityId());

        if (!$fastEstShipment->getOrder()->isShippedBy(FastEst::SHIPPING_METHOD)) {
            return;
        }

        $response = $this->deliveryRepository->create($fastEstShipment);
        $statusReturn = $response->getStatusReturn();

        if (!$statusReturn->isOk()) {
            throw new LocalizedException(__($statusReturn->getErrorDescription()));
        }
    }
}

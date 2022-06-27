<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Service;

use Collections\Exceptions\InvalidArgumentException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\FastEst\Api\DeliveryRepositoryInterface;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Sales\Api\OrderRepositoryInterface;

class CreateNewDelivery
{
    private OrderRepositoryInterface $orderRepository;
    private DeliveryRepositoryInterface $deliveryRepository;
    private SendTrackingLink $sendTrackingLinkService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        DeliveryRepositoryInterface $deliveryRepository,
        SendTrackingLink $sendTrackingLinkService
    ) {
        $this->orderRepository = $orderRepository;
        $this->deliveryRepository = $deliveryRepository;
        $this->sendTrackingLinkService = $sendTrackingLinkService;
    }

    /**
     * @throws InvalidArgumentException
     * @throws LocalizedException
     */
    public function create(OrderInterface $order): void
    {
        $fastEstOrder = $this->orderRepository->get((int) $order->getEntityId());

        if (!$fastEstOrder->isShippedBy(FastEst::SHIPPING_METHOD)) {
            return;
        }

        $response = $this->deliveryRepository->create($fastEstOrder);
        $statusReturn = $response->getStatusReturn();

        if ($statusReturn->isOk()) {
            $fastEstOrder->resetShippingSyncFailures();
            $fastEstOrder->setIsParked(!$fastEstOrder->isPaid());

            $this->sendTrackingLinkService->execute($fastEstOrder);
        } else {
            $fastEstOrder->increaseShippingSyncFailures();
        }

        $this->orderRepository->save($fastEstOrder);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Service;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\FastEst\Api\DeliveryRepositoryInterface;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Sales\Api\OrderRepositoryInterface;

class ConfirmParkedDelivery
{
    private OrderRepositoryInterface $orderRepository;
    private DeliveryRepositoryInterface $deliveryRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        DeliveryRepositoryInterface $deliveryRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->deliveryRepository = $deliveryRepository;
    }

    /**
     * @throws LocalizedException
     */
    public function confirm(OrderInterface $order): void
    {
        $fastEstOrder = $this->orderRepository->get((int) $order->getEntityId());

        if (!$fastEstOrder->isShippedBy(FastEst::SHIPPING_METHOD)) {
            return;
        }

        $response = $this->deliveryRepository->confirmParked($fastEstOrder);
        $statusReturn = $response->getStatusReturn();

        if ($statusReturn->isOk()) {
            $fastEstOrder->resetShippingSyncFailures();
            $fastEstOrder->setIsParked(false);
        } else {
            $fastEstOrder->increaseShippingSyncFailures();
        }

        $this->orderRepository->save($fastEstOrder);
    }
}

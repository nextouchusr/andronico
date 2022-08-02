<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\FastEst\Service\SendTrackingLink;
use Nextouch\Sales\Api\OrderRepositoryInterface;

class SendFastEstTrackingLink implements ObserverInterface
{
    private OrderRepositoryInterface $orderRepository;
    private SendTrackingLink $sendTrackingLinkService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SendTrackingLink $sendTrackingLinkService
    ) {
        $this->orderRepository = $orderRepository;
        $this->sendTrackingLinkService = $sendTrackingLinkService;
    }

    public function execute(Observer $observer): void
    {
        $order = $observer->getData('order');
        $fastEstOrder = $this->orderRepository->get((int) $order->getEntityId());

        if (!$fastEstOrder->isShippedBy(FastEst::SHIPPING_METHOD)) {
            return;
        }

        $this->sendTrackingLinkService->execute($order);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Plugin\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\ShipOrderInterface;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\FastEst\Service\SendTrackingLink;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;

class SendFastEstTrackingLink
{
    private OrderRepositoryInterface $orderRepository;
    private SendTrackingLink $sendTrackingLinkService;
    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SendTrackingLink $sendTrackingLinkService,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->sendTrackingLinkService = $sendTrackingLinkService;
        $this->logger = $logger;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterExecute(ShipOrderInterface $subject, string $result, $orderId): string
    {
        try {
            $fastEstOrder = $this->orderRepository->get($orderId);

            if (!$fastEstOrder->isShippedBy(FastEst::SHIPPING_METHOD)) {
                return $result;
            }

            $this->sendTrackingLinkService->execute($fastEstOrder);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        return $result;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Plugin\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\FastEst\Service\SendTrackingLink;
use Nextouch\Sales\Api\OrderRepositoryInterface as NextouchOrderRepositoryInterface;
use Nextouch\Sales\Model\Order\Status;
use Psr\Log\LoggerInterface;

class SendFastEstTrackingLink
{
    private NextouchOrderRepositoryInterface $nextouchOrderRepository;
    private SendTrackingLink $sendTrackingLinkService;
    private LoggerInterface $logger;

    public function __construct(
        NextouchOrderRepositoryInterface $nextouchOrderRepository,
        SendTrackingLink $sendTrackingLinkService,
        LoggerInterface $logger
    ) {
        $this->nextouchOrderRepository = $nextouchOrderRepository;
        $this->sendTrackingLinkService = $sendTrackingLinkService;
        $this->logger = $logger;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterSave(OrderRepositoryInterface $subject, OrderInterface $result): OrderInterface
    {
        try {
            $fastEstOrder = $this->nextouchOrderRepository->get((int) $result->getEntityId());

            $isShippedByFastEst = $fastEstOrder->isShippedBy(FastEst::SHIPPING_METHOD);
            $isShippedStatus = $fastEstOrder->getStatus() === Status::SHIPPED['status'];
            $isShippedState = $fastEstOrder->getState() === Status::SHIPPED['state'];

            if ($isShippedByFastEst && $isShippedStatus && $isShippedState) {
                $this->sendTrackingLinkService->execute($fastEstOrder);
            }
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        return $result;
    }
}

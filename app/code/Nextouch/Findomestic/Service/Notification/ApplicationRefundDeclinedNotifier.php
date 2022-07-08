<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Notification;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Findomestic\Model\Mail\RefundDeclined as RefundDeclinedMail;
use Nextouch\Findomestic\Model\Request\Installment\Notification as NotificationRequest;
use Nextouch\Rma\Api\RmaRepositoryInterface;
use Psr\Log\LoggerInterface;

class ApplicationRefundDeclinedNotifier implements InstallmentNotifierInterface
{
    private RmaRepositoryInterface $rmaRepository;
    private RefundDeclinedMail $refundDeclinedMail;
    private ManagerInterface $eventManager;
    private LoggerInterface $logger;

    public function __construct(
        RmaRepositoryInterface $rmaRepository,
        RefundDeclinedMail $refundDeclinedMail,
        ManagerInterface $eventManager,
        LoggerInterface $logger
    ) {
        $this->rmaRepository = $rmaRepository;
        $this->refundDeclinedMail = $refundDeclinedMail;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
    }

    public function execute(NotificationRequest $request): void
    {
        try {
            $this->eventManager->dispatch('findomestic_application_refund_declined_start', [
                'request' => $request,
            ]);

            $notificationId = (string) $request->getNotificationEvent()->getNotificationId();
            $return = $this->rmaRepository->getByIncrementId($notificationId);

            $return->setFindomesticRefundStatusDeclined();
            $this->rmaRepository->save($return);

            $this->eventManager->dispatch('findomestic_application_refund_declined_end', [
                'request' => $request,
                'return' => $return,
            ]);

            $this->refundDeclinedMail->setReturn($return)->send();
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}

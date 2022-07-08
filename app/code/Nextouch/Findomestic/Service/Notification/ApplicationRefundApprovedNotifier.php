<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Notification;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Findomestic\Model\Mail\RefundApproved as RefundApprovedMail;
use Nextouch\Findomestic\Model\Request\Installment\Notification as NotificationRequest;
use Nextouch\Rma\Api\RmaRepositoryInterface;
use Psr\Log\LoggerInterface;

class ApplicationRefundApprovedNotifier implements InstallmentNotifierInterface
{
    private RmaRepositoryInterface $rmaRepository;
    private RefundApprovedMail $refundApprovedMail;
    private ManagerInterface $eventManager;
    private LoggerInterface $logger;

    public function __construct(
        RmaRepositoryInterface $rmaRepository,
        RefundApprovedMail $refundApprovedMail,
        ManagerInterface $eventManager,
        LoggerInterface $logger
    ) {
        $this->rmaRepository = $rmaRepository;
        $this->refundApprovedMail = $refundApprovedMail;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
    }

    public function execute(NotificationRequest $request): void
    {
        try {
            $this->eventManager->dispatch('findomestic_application_refund_approved_start', [
                'request' => $request,
            ]);

            $notificationId = (string) $request->getNotificationEvent()->getNotificationId();
            $return = $this->rmaRepository->getByIncrementId($notificationId);

            $return->setFindomesticRefundStatusApproved();
            $this->rmaRepository->save($return);

            $this->eventManager->dispatch('findomestic_application_refund_approved_end', [
                'request' => $request,
                'return' => $return,
            ]);

            $this->refundApprovedMail->setReturn($return)->send();
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}

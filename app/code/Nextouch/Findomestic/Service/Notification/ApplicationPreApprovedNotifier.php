<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Notification;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\GuestCartManagementInterface;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Nextouch\Findomestic\Model\Request\Installment\Notification as NotificationRequest;
use Nextouch\Quote\Api\CartReservationRepositoryInterface;
use Psr\Log\LoggerInterface;

class ApplicationPreApprovedNotifier implements InstallmentNotifierInterface
{
    private CartReservationRepositoryInterface $cartReservationRepository;
    private CartRepositoryInterface $cartRepository;
    private CartManagementInterface $cartManagement;
    private QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId;
    private GuestCartManagementInterface $guestCartManagement;
    private ManagerInterface $eventManager;
    private LoggerInterface $logger;

    public function __construct(
        CartReservationRepositoryInterface $cartReservationRepository,
        CartRepositoryInterface $cartRepository,
        CartManagementInterface $cartManagement,
        QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId,
        GuestCartManagementInterface $guestCartManagement,
        ManagerInterface $eventManager,
        LoggerInterface $logger
    ) {
        $this->cartReservationRepository = $cartReservationRepository;
        $this->cartRepository = $cartRepository;
        $this->cartManagement = $cartManagement;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
        $this->guestCartManagement = $guestCartManagement;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
    }

    public function execute(NotificationRequest $request): void
    {
        try {
            $this->eventManager->dispatch('findomestic_application_preapprove_start', [
                'request' => $request,
            ]);

            if ($this->isGuestFindomesticOrder($request)) {
                $orderId = $this->placeGuestFindomesticOrder($request);
            } else {
                $orderId = $this->placeFindomesticOrder($request);
            }

            $this->eventManager->dispatch('findomestic_application_preapprove_end', [
                'request' => $request,
                'orderId' => $orderId,
            ]);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }

    private function isGuestFindomesticOrder(NotificationRequest $request): bool
    {
        $orderNumber = $request->getOrderNumber();
        $quote = $this->cartReservationRepository->getByReservedOrderId($orderNumber);

        return (bool) $quote->getCustomerIsGuest();
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpCastIsUnnecessaryInspection
     */
    private function placeGuestFindomesticOrder(NotificationRequest $request): int
    {
        $orderNumber = $request->getOrderNumber();

        $quote = $this->cartReservationRepository->getByReservedOrderId($orderNumber);
        $quote->setFindomesticApplicationId($request->getApplicationId());
        $quote->setFindomesticIssuerInstallmentId($request->getIssuerInstallmentId());
        $this->cartRepository->save($quote);

        $maskedQuoteId = $this->quoteIdToMaskedQuoteId->execute((int) $quote->getId());

        return (int) $this->guestCartManagement->placeOrder($maskedQuoteId);
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpCastIsUnnecessaryInspection
     */
    private function placeFindomesticOrder(NotificationRequest $request): int
    {
        $orderNumber = $request->getOrderNumber();

        $quote = $this->cartReservationRepository->getByReservedOrderId($orderNumber);
        $quote->setFindomesticApplicationId($request->getApplicationId());
        $quote->setFindomesticIssuerInstallmentId($request->getIssuerInstallmentId());
        $this->cartRepository->save($quote);

        return (int) $this->cartManagement->placeOrder($quote->getId());
    }
}

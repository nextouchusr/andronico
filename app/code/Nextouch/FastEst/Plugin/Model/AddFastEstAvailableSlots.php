<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Plugin\Model;

use Amasty\Deliverydate\Model\DeliverydateConfigProvider;
use Magento\Checkout\Model\Session as CheckoutSession;
use Nextouch\FastEst\Api\AppointmentRepositoryInterface;
use Psr\Log\LoggerInterface;

class AddFastEstAvailableSlots
{
    private CheckoutSession $checkoutSession;
    private AppointmentRepositoryInterface $appointmentRepository;
    private LoggerInterface $logger;

    public function __construct(
        CheckoutSession $checkoutSession,
        AppointmentRepositoryInterface $appointmentRepository,
        LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->appointmentRepository = $appointmentRepository;
        $this->logger = $logger;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetConfig(DeliverydateConfigProvider $subject, array $result): array
    {
        try {
            $cart = $this->checkoutSession->getQuote();
            $availableSlots = $this->appointmentRepository->getAvailableSlots($cart)->getSlotResponse()->toArray();
            $result['amasty']['deliverydate']['fast_est_available_slots'] = $availableSlots;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $result;
    }
}

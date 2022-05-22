<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Plugin\Sales\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderManagementInterface;
use Nextouch\Findomestic\Service\Installment\CancelInstallment as CancelInstallmentService;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class CancelOrderInstallment
{
    private OrderRepositoryInterface $orderRepository;
    private CancelInstallmentService $cancelInstallmentService;
    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CancelInstallmentService $cancelInstallmentService,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->cancelInstallmentService = $cancelInstallmentService;
        $this->logger = $logger;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterCancel(OrderManagementInterface $subject, bool $result, $orderId): bool
    {
        try {
            /** @var Order $order */
            $order = $this->orderRepository->get((int) $orderId);

            if (!$order->hasFindomesticPayment()) {
                return $result;
            }

            $this->cancelInstallmentService->cancel($order);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }

        return $result;
    }
}

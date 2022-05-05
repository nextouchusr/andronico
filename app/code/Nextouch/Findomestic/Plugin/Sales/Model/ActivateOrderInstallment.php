<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Plugin\Sales\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\ShipOrderInterface;
use Nextouch\Findomestic\Service\Installment\ActivateInstallment as ActivateInstallmentService;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class ActivateOrderInstallment
{
    private OrderRepositoryInterface $orderRepository;
    private ActivateInstallmentService $activateInstallmentService;
    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ActivateInstallmentService $activateInstallmentService,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->activateInstallmentService = $activateInstallmentService;
        $this->logger = $logger;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterExecute(ShipOrderInterface $subject, string $result, int $orderId): string
    {
        try {
            /** @var Order $order */
            $order = $this->orderRepository->get($orderId);

            $this->activateInstallmentService->activate($order);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        return $result;
    }
}

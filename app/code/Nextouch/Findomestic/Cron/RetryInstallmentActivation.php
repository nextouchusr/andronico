<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Cron;

use Magento\Framework\Exception\LocalizedException;
use Nextouch\Findomestic\Service\Installment\ActivateInstallment as ActivateInstallmentService;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Model\Order;
use Nextouch\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;
use function Lambdish\Phunctional\each;

class RetryInstallmentActivation
{
    private CollectionFactory $collectionFactory;
    private ActivateInstallmentService $activateInstallmentService;
    private LoggerInterface $logger;

    public function __construct(
        CollectionFactory $collectionFactory,
        ActivateInstallmentService $activateInstallmentService,
        LoggerInterface $logger
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->activateInstallmentService = $activateInstallmentService;
        $this->logger = $logger;
    }

    public function execute(): void
    {
        $orders = $this->collectionFactory->create()->addFilter(
            OrderInterface::FINDOMESTIC_APPLICATION_STATUS,
            OrderInterface::FINDOMESTIC_APPLICATION_STATUS_TO_ACTIVATE
        )->getItems();

        each(fn(Order $item) => $this->activateInstallment($item), $orders);
    }

    private function activateInstallment(Order $order): void
    {
        try {
            $this->activateInstallmentService->activate($order);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}

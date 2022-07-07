<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Notification;

use Magento\Framework\DB\Transaction;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Service\InvoiceService;
use Nextouch\Findomestic\Model\Request\Installment\Notification as NotificationRequest;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;
use Nextouch\Sales\Model\Order\Status;
use Psr\Log\LoggerInterface;

class ApplicationCompletedNotifier implements InstallmentNotifierInterface
{
    private OrderRepositoryInterface $orderRepository;
    private InvoiceService $invoiceService;
    private Transaction $transaction;
    private ManagerInterface $eventManager;
    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InvoiceService $invoiceService,
        Transaction $transaction,
        ManagerInterface $eventManager,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
    }

    public function execute(NotificationRequest $request): void
    {
        try {
            $this->eventManager->dispatch('findomestic_application_complete_start', [
                'request' => $request,
            ]);

            $order = $this->payOrder($request);

            $this->eventManager->dispatch('findomestic_application_complete_end', [
                'request' => $request,
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws LocalizedException
     * @throws \Exception
     */
    private function payOrder(NotificationRequest $request): Order
    {
        $invoice = $this->registerInvoice($request);

        /** @var Order $order */
        $order = $invoice->getOrder();

        $order->setCustomerNoteNotify(false);
        $order->setStatus(Status::PAID['status']);
        $order->setState(Status::PAID['state']);
        $order->setFindomesticApplicationToActivate();
        $this->transaction->addObject($invoice)->addObject($order)->save();

        return $order;
    }

    /**
     * @throws LocalizedException
     */
    private function registerInvoice(NotificationRequest $request): Invoice
    {
        $orderNumber = $request->getOrderNumber();

        /** @var Order $order */
        $order = $this->orderRepository->getByIncrementId($orderNumber);

        if (!$order->canInvoice()) {
            throw new LocalizedException(__('The order does not allow an invoice to be created.'));
        }

        $invoice = $this->invoiceService->prepareInvoice($order);
        $invoice->setRequestedCaptureCase(Invoice::CAPTURE_OFFLINE);
        $invoice->register();

        return $invoice;
    }
}

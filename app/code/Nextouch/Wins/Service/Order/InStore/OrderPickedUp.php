<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order\InStore;

use Magento\Framework\DB\Transaction;
use Magento\Framework\Exception\LocalizedException;
use Magento\InventoryInStorePickupSalesApi\Api\NotifyOrdersAreReadyForPickupInterface;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Service\InvoiceService;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;
use Nextouch\Sales\Model\Order\Status;

class OrderPickedUp implements OrderActionInterface
{
    private OrderRepositoryInterface $orderRepository;
    private InvoiceService $invoiceService;
    private Transaction $transaction;
    private NotifyOrdersAreReadyForPickupInterface $notifyOrdersAreReadyForPickup;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InvoiceService $invoiceService,
        Transaction $transaction,
        NotifyOrdersAreReadyForPickupInterface $notifyOrdersAreReadyForPickup
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->notifyOrdersAreReadyForPickup = $notifyOrdersAreReadyForPickup;
    }

    /**
     * @throws LocalizedException
     * @throws \Exception
     */
    public function execute(string $orderId): void
    {
        /** @var Order $order */
        $order = $this->orderRepository->getByIncrementId($orderId);

        if (!$order->isPaid()) {
            $this->payOrder($order);
        }

        $this->shipOrder($order);
    }

    /**
     * @throws LocalizedException
     * @throws \Exception
     */
    private function payOrder(Order $order): void
    {
        $invoice = $this->registerInvoice($order);

        $order->setCustomerNoteNotify(false);
        $order->setStatus(Status::PAID['status']);
        $order->setState(Status::PAID['state']);
        $this->transaction->addObject($invoice)->addObject($order)->save();
    }

    /**
     * @throws LocalizedException
     */
    private function registerInvoice(Order $order): Invoice
    {
        if (!$order->canInvoice()) {
            throw new LocalizedException(__('The order does not allow an invoice to be created.'));
        }

        $invoice = $this->invoiceService->prepareInvoice($order);
        $invoice->setRequestedCaptureCase(Invoice::CAPTURE_OFFLINE);
        $invoice->register();

        return $invoice;
    }

    /**
     * @throws LocalizedException
     */
    private function shipOrder(Order $order): void
    {
        $this->notifyOrdersAreReadyForPickup->execute([$order->getEntityId()]);

        $order->setStatus(Status::COMPLETE['status']);
        $order->setState(Status::COMPLETE['state']);
        $this->orderRepository->save($order);
    }
}

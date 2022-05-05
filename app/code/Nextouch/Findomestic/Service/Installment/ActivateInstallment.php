<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Installment;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Shipment;
use Nextouch\Findomestic\Api\InstallmentManagementInterface;
use Nextouch\Findomestic\Helper\FindomesticConfig;
use Nextouch\Findomestic\Model\Request\Installment\Activate as ActivateRequest;
use Nextouch\Findomestic\Model\Response\Installment\Activate as ActivateResponse;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;

class ActivateInstallment
{
    private OrderRepositoryInterface $orderRepository;
    private InstallmentManagementInterface $installmentManagement;
    private FindomesticConfig $config;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InstallmentManagementInterface $installmentManagement,
        FindomesticConfig $config
    ) {
        $this->orderRepository = $orderRepository;
        $this->installmentManagement = $installmentManagement;
        $this->config = $config;
    }

    /**
     * @throws LocalizedException
     * @throws \Exception
     */
    public function activate(Order $order): ActivateResponse
    {
        if (!$order->canActivateInstallment()) {
            throw new LocalizedException(
                __('Impossible to activate installment for order #%1', $order->getIncrementId())
            );
        }

        $request = $this->createRequest($order);

        $response = $this->installmentManagement->activate($request);
        $statusReturn = $response->getStatusReturn();

        if (!$statusReturn->isOk()) {
            throw new LocalizedException(__($statusReturn->getErrorDescription()));
        }

        $order->setFindomesticApplicationActive();
        $this->orderRepository->save($order);

        return $response;
    }

    /**
     * @throws \Exception
     * @throws LocalizedException
     */
    private function createRequest(Order $order): ActivateRequest
    {
        /** @var Invoice $invoice */
        $invoice = $order->getInvoiceCollection()->getFirstItem();

        if (!$invoice->getId()) {
            throw new LocalizedException(__('The order does not have an invoice created.'));
        }

        /** @var Shipment $shipment */
        $shipment = $order->getShipmentsCollection()->getFirstItem();

        if (!$shipment->getId()) {
            throw new LocalizedException(__('The order does not have a shipment created.'));
        }

        $shipmentDate = new \DateTime($shipment->getCreatedAt());

        return new ActivateRequest(
            $order->getFindomesticIssuerInstallmentId(),
            $this->config->getVendorId(),
            $order->getIncrementId(),
            gmdate('Y-m-d\TH:i:s.\0\0\0\Z', $shipmentDate->getTimestamp()),
            $invoice->getIncrementId()
        );
    }
}

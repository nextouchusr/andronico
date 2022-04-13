<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Installment;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order\Invoice;
use Nextouch\Findomestic\Api\InstallmentManagementInterface;
use Nextouch\Findomestic\Helper\FindomesticConfig;
use Nextouch\Findomestic\Model\Request\Installment\Activate as ActivateRequest;
use Nextouch\Findomestic\Model\Response\Installment\Activate as ActivateResponse;
use Nextouch\Sales\Model\Order;

class ActivateInstallment
{
    private InstallmentManagementInterface $installmentManagement;
    private FindomesticConfig $config;

    public function __construct(
        InstallmentManagementInterface $installmentManagement,
        FindomesticConfig $config
    ) {
        $this->installmentManagement = $installmentManagement;
        $this->config = $config;
    }

    /**
     * @throws LocalizedException
     */
    public function activate(Order $order): ActivateResponse
    {
        /** @var Invoice $invoice */
        $invoice = $order->getInvoiceCollection()->getFirstItem();

        if (!$invoice->getId()) {
            throw new LocalizedException(__('The order does not have an invoice created.'));
        }

        $request = new ActivateRequest(
            $order->getFindomesticIssuerInstallmentId(),
            $this->config->getVendorId(),
            $order->getIncrementId(),
            gmdate('Y-m-d\TH:i:s.\0\0\0\Z', strtotime('+3 hour')), // TODO: replace placeholder
            $invoice->getIncrementId()
        );

        $response = $this->installmentManagement->activate($request);
        $statusReturn = $response->getStatusReturn();

        if (!$statusReturn->isOk()) {
            throw new LocalizedException(__($statusReturn->getErrorDescription()));
        }

        return $response;
    }
}

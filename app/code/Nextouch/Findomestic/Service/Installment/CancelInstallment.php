<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Installment;

use Magento\Framework\Exception\LocalizedException;
use Nextouch\Findomestic\Api\InstallmentManagementInterface;
use Nextouch\Findomestic\Helper\FindomesticConfig;
use Nextouch\Findomestic\Model\Request\Installment\Cancel as CancelRequest;
use Nextouch\Findomestic\Model\Response\Installment\Cancel as CancelResponse;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\Order;

class CancelInstallment
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
     */
    public function cancel(Order $order): CancelResponse
    {
        if (!$order->canCancelInstallment()) {
            throw new LocalizedException(
                __('Impossible to cancel installment for order #%1', $order->getIncrementId())
            );
        }

        $request = $this->createRequest($order);

        $response = $this->installmentManagement->cancel($request);
        $statusReturn = $response->getStatusReturn();

        if (!$statusReturn->isOk()) {
            throw new LocalizedException(__($statusReturn->getErrorDescription()));
        }

        $order->setFindomesticApplicationCancel();
        $this->orderRepository->save($order);

        return $response;
    }

    private function createRequest(Order $order): CancelRequest
    {
        return new CancelRequest(
            $order->getFindomesticIssuerInstallmentId(),
            (float) $order->getGrandTotal(),
            $this->config->getVendorId(),
            $order->getIncrementId(),
            true,
            'Customer has requested the cancellation of the order'
        );
    }
}

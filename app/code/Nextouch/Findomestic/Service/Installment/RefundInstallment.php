<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Service\Installment;

use Magento\Framework\Exception\LocalizedException;
use Nextouch\Findomestic\Api\InstallmentManagementInterface;
use Nextouch\Findomestic\Helper\FindomesticConfig;
use Nextouch\Findomestic\Model\Request\Installment\Refund as RefundRequest;
use Nextouch\Findomestic\Model\Response\Installment\Refund as RefundResponse;
use Nextouch\Rma\Api\RmaRepositoryInterface;
use Nextouch\Rma\Model\Rma;

class RefundInstallment
{
    private RmaRepositoryInterface $rmaRepository;
    private InstallmentManagementInterface $installmentManagement;
    private FindomesticConfig $config;

    public function __construct(
        RmaRepositoryInterface $rmaRepository,
        InstallmentManagementInterface $installmentManagement,
        FindomesticConfig $config
    ) {
        $this->rmaRepository = $rmaRepository;
        $this->installmentManagement = $installmentManagement;
        $this->config = $config;
    }

    /**
     * @throws LocalizedException
     */
    public function refund(Rma $return): RefundResponse
    {
        if (!$return->canRefundInstallment()) {
            throw new LocalizedException(
                __('Impossible to refund installment for return #%1', $return->getIncrementId())
            );
        }

        $request = $this->createRequest($return);

        $response = $this->installmentManagement->refund($request);
        $statusReturn = $response->getStatusReturn();

        if (!$statusReturn->isOk()) {
            throw new LocalizedException(__($statusReturn->getErrorDescription()));
        }

        $return->setFindomesticRefundStatusPending();
        $this->rmaRepository->save($return);

        return $response;
    }

    /**
     * @noinspection PhpCastIsUnnecessaryInspection
     */
    private function createRequest(Rma $return): RefundRequest
    {
        $order = $return->getOrder();
        $amountApproved = $return->getAmountApproved();
        $financedAmount = (float) $order->getGrandTotal();
        $amountRefunded = (float) $order->getPayment()->getAmountRefunded();
        $isComplete = ($amountRefunded + $amountApproved) >= $financedAmount;

        return new RefundRequest(
            $order->getFindomesticIssuerInstallmentId(),
            $order->getIncrementId(),
            $amountApproved,
            $financedAmount,
            $isComplete,
            $return->getIncrementId(),
            $this->config->getVendorId(),
            ''
        );
    }
}

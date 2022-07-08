<?php
declare(strict_types=1);

namespace Nextouch\Rma\Controller\Adminhtml\Rma;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Registry;
use Magento\Rma\Controller\Adminhtml\Rma as BaseRma;
use Magento\Rma\Model\Rma\RmaDataMapper;
use Magento\Rma\Model\Rma\Source\Status;
use Magento\Rma\Model\Shipping\LabelService;
use Magento\Shipping\Helper\Carrier;
use Nextouch\Findomestic\Service\Installment\RefundInstallment as RefundInstallmentService;
use Nextouch\Rma\Api\RmaRepositoryInterface;
use Nextouch\Rma\Model\Rma;
use Psr\Log\LoggerInterface;

class SendFindomesticRefund extends BaseRma implements HttpGetActionInterface
{
    private RmaRepositoryInterface $rmaRepository;
    private RefundInstallmentService $refundInstallmentService;
    private LoggerInterface $logger;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FileFactory $fileFactory,
        Filesystem $filesystem,
        Carrier $carrierHelper,
        LabelService $labelService,
        RmaDataMapper $rmaDataMapper,
        RmaRepositoryInterface $rmaRepository,
        RefundInstallmentService $refundInstallmentService,
        LoggerInterface $logger
    ) {
        parent::__construct(
            $context,
            $coreRegistry,
            $fileFactory,
            $filesystem,
            $carrierHelper,
            $labelService,
            $rmaDataMapper
        );
        $this->rmaRepository = $rmaRepository;
        $this->refundInstallmentService = $refundInstallmentService;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            $return = $this->getReturn();

            if ($return->getStatus() !== Status::STATE_PROCESSED_CLOSED) {
                $message = __('Impossible to send Findomestic refund request. Return has an invalid status.');
                throw new LocalizedException($message);
            }

            $this->refundInstallmentService->refund($return);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->logger->error($e->getMessage());
        }

        return $this->_redirect('adminhtml/*/');
    }

    /**
     * @throws LocalizedException
     */
    private function getReturn(): Rma
    {
        $model = $this->_initModel('rma_id');

        return $this->rmaRepository->get((int) $model->getEntityId());
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Rma\Block\Adminhtml\Rma;

use Magento\Rma\Model\Rma\Source\Status;

class Edit extends \Magento\Rma\Block\Adminhtml\Rma\Edit
{
    protected function _construct(): void
    {
        parent::_construct();
        $this->addFindomesticRefundButton();
    }

    private function addFindomesticRefundButton(): void
    {
        if ($this->canAddFindomesticRefundButton()) {
            $this->buttonList->add('send-findomestic-refund', [
                'label' => __('Send Findomestic Refund'),
                'class' => 'send-findomestic-refund',
                'onclick' => 'setLocation(\'' . $this->getFindomesticRefundUrl() . '\')',
            ], 102);
        }
    }

    private function canAddFindomesticRefundButton(): bool
    {
        $isFindomestic = $this->getRma()->getOrder()->getPayment()->getMethod() === 'findomestic_paymentservice';
        $isClosed = $this->getRma()->getStatus() === Status::STATE_PROCESSED_CLOSED;

        return $isFindomestic && $isClosed;
    }

    private function getFindomesticRefundUrl(): string
    {
        return $this->getUrl(
            'adminhtml/*/sendFindomesticRefund',
            ['rma_id' => $this->getRma()->getId()]
        );
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Rma\Block\Adminhtml\Rma;

use Magento\Rma\Model\Rma\Source\Status;

class Edit extends \Magento\Rma\Block\Adminhtml\Rma\Edit
{
    protected function _construct(): void
    {
        parent::_construct();

        if ($this->getRma()->getStatus() === Status::STATE_PROCESSED_CLOSED) {
            $this->buttonList->add('send-findomestic-refund', [
                'label' => __('Send Findomestic Refund'),
                'class' => 'send-findomestic-refund',
                'onclick' => 'setLocation(\'' . $this->getSendFindomesticRefundUrl() . '\')',
            ], 102);
        }
    }

    public function getSendFindomesticRefundUrl(): string
    {
        return $this->getUrl(
            'adminhtml/*/sendFindomesticRefund',
            ['rma_id' => $this->getRma()->getId()]
        );
    }
}

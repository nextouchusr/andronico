<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer\Findomestic;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Nextouch\Sales\Model\Order;
use Nextouch\Wins\Service\Order\SendCurrentOrderStatus;

class ChangeOrderStatusToPaid implements ObserverInterface
{
    private SendCurrentOrderStatus $sendCurrentOrderStatus;

    public function __construct(SendCurrentOrderStatus $sendCurrentOrderStatus)
    {
        $this->sendCurrentOrderStatus = $sendCurrentOrderStatus;
    }

    public function execute(Observer $observer): void
    {
        /** @var Order $order */
        $order = $observer->getData('order');
        $transactionID = $order->getFindomesticIssuerInstallmentId();

        $this->sendCurrentOrderStatus->execute($order, $transactionID);
    }
}

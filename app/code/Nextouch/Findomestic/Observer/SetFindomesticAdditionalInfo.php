<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Sales\Api\Data\OrderInterface;

class SetFindomesticAdditionalInfo implements ObserverInterface
{
    public function execute(Observer $observer): void
    {
        /** @var Quote $quote */
        $quote = $observer->getData('quote');
        $applicationId = $quote->getData(CartInterface::FINDOMESTIC_APPLICATION_ID);
        $issuerInstallmentId = $quote->getData(CartInterface::FINDOMESTIC_ISSUER_INSTALLMENT_ID);

        /** @var Order $order */
        $order = $observer->getData('order');
        $order->setData(OrderInterface::FINDOMESTIC_APPLICATION_ID, $applicationId);
        $order->setData(OrderInterface::FINDOMESTIC_ISSUER_INSTALLMENT_ID, $issuerInstallmentId);
    }
}

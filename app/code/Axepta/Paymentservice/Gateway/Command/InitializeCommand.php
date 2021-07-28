<?php

namespace Axepta\Paymentservice\Gateway\Command;

use Magento\Braintree\Gateway\SubjectReader;
use Magento\Braintree\Model\Adapter\BraintreeAdapterFactory;
use Magento\Braintree\Model\Adapter\BraintreeSearchAdapter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Helper\ContextHelper;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;

/**
 * Class CaptureStrategyCommand
 * @SuppressWarnings(PHPMD)
 */
class InitializeCommand implements CommandInterface
{
    protected $objectManager;

    /**
     * @inheritdoc
     */
    public function execute(array $commandSubject)
    {
        $commandSubject['payment']->getPayment()->getOrder()->setCanSendNewEmailFlag(false);

        /*
        $payment = $commandSubject['payment']->getPayment();
        $commandSubject['payment']->getPayment()->getOrder()->setCanSendNewEmailFlag(false);
        $commandSubject['stateObject']->setData('is_notified', false);
        */
    }
}

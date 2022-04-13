<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Controller;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Nextouch\Findomestic\Api\InstallmentManagementInterface;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use Psr\Log\LoggerInterface;

abstract class Payment extends Action
{
    protected CheckoutSession $checkoutSession;
    protected CartRepositoryInterface $cartRepository;
    protected QuoteCollectionFactory $quoteCollectionFactory;
    protected InstallmentManagementInterface $installmentManagement;
    protected JsonFactory $resultJsonFactory;
    protected LoggerInterface $logger;

    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        CartRepositoryInterface $cartRepository,
        QuoteCollectionFactory $quoteCollectionFactory,
        InstallmentManagementInterface $installmentManagement,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->installmentManagement = $installmentManagement;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
    }

    /**
     * @throws LocalizedException
     */
    protected function getQuote(): CartInterface
    {
        $quote = $this->getCheckout()->getQuote();

        /** @var CartInterface $result */
        $result = $this->quoteCollectionFactory->create()->getItemById((int) $quote->getId());

        return $result;
    }

    protected function getCheckout(): CheckoutSession
    {
        return $this->checkoutSession;
    }
}

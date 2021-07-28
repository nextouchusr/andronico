<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Controller\Payment;

use Axepta\Paymentservice\Controller\Payment;
use Magento\Framework\App\ResponseInterface;

class Error extends Payment
{
    protected function getCheckout()
    {
        return $this->_objectManager->get(\Magento\Checkout\Model\Session::class);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $lastQuoteId = $this->getCheckout()->getLastQuoteId();
        $quoteRepository = $this->_objectManager->get('\Magento\Quote\Api\CartRepositoryInterface');
        $quoteObject = $quoteRepository->get($lastQuoteId);
        $quoteObject->setIsActive(true)->save();

        $this->messageManager->addErrorMessage(__('Errore durante l\'esecuzione del pagamento'));
        return $this->_redirect('checkout/cart', ['_secure' => true]);
    }
}

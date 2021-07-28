<?php

/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Controller\Payment;

use Axepta\Paymentservice\Controller\Payment;
use Magento\Framework\App\ResponseInterface;

class Success extends Payment
{

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
        // Disabilito il carrello
        $lastQuoteId = $this->getCheckout()->getLastQuoteId();
        $quoteRepository = $this->_objectManager->get('\Magento\Quote\Api\CartRepositoryInterface');
        $quoteObject = $quoteRepository->get($lastQuoteId);
        $quoteObject->setIsActive(false)->save();
/*
        // SERVE PER LA 2.4.1 ?????

        $checkout = $this->getCheckout();
        $checkout->clearQuote();
        $checkout->clearStorage();
        $checkout->restoreQuote();
 */
        $successText = 'Pagamento completato con successo';
        $this->messageManager->addSuccessMessage(__($successText));
        return $this->_redirect('checkout/onepage/success');
    }
}

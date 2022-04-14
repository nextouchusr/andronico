<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Controller\Payment;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Findomestic\Controller\Payment;
use Nextouch\Findomestic\Model\Request\Installment\Create;
use Nextouch\Quote\Api\Data\CartInterface;

class Redirect extends Payment
{
    public function execute()
    {
        try {
            if (!$this->canProcessRequest()) {
                return $this->_redirect('checkout/cart', ['_secure' => true]);
            }

            $quote = $this->reserveOrderId();
            $request = Create::fromDomain($quote);
            $data = $this->installmentManagement->create($request);

            return $this->resultJsonFactory->create()->setData(['data' => $data->toArray()]);
        } catch (\Exception $e) {
            $message = __('Error with your payment. Please try again later!' . $e->getMessage());
            $this->logger->error($message);
            $this->messageManager->addErrorMessage($message);

            return $this->_redirect('checkout/cart', ['_secure' => true]);
        }
    }

    private function canProcessRequest(): bool
    {
        /** @var Http $request */
        $request = $this->getRequest();

        return $request->isAjax() && $request->isPost();
    }

    /**
     * @throws LocalizedException
     */
    private function reserveOrderId(): CartInterface
    {
        $quote = $this->getQuote();

        $nextOrderId = $quote->reserveOrderId()->getReservedOrderId();
        $quote->setReservedOrderId($nextOrderId);

        $this->cartRepository->save($quote);

        return $quote;
    }
}

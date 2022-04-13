<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Controller\Payment;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Findomestic\Controller\Payment;
use Nextouch\Findomestic\Model\Common\Callback;
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
            $callbacks = $this->getCallbacks();
            $request = Create::fromDomain($quote, $callbacks);
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

    private function getCallbacks(): array
    {
        return [
            [
                'url' => $this->_url->getRouteUrl('/checkout'),
                'use' => Callback::USE_TYPE_CANCEL,
                'action' => Callback::ACTION_TYPE_MANUAL,
                'label' => __('Back to shop')->getText(),
                'description' => __('Cancel the operation and go back to the shop')->getText(),
            ],
        ];
    }
}

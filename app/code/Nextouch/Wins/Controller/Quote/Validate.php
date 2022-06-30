<?php
declare(strict_types=1);

namespace Nextouch\Wins\Controller\Quote;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Phrase;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Nextouch\Wins\Model\Order\PickAndPayInfo;
use Nextouch\Wins\Service\Quote\ValidateQuoteSalableQty as ValidateQuoteSalableQtyService;
use function Lambdish\Phunctional\all;
use function Lambdish\Phunctional\first;
use function Lambdish\Phunctional\reduce;

class Validate extends Action
{
    private CheckoutSession $checkoutSession;
    private ValidateQuoteSalableQtyService $validateQuoteSalableQtyService;
    private JsonFactory $resultJsonFactory;

    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        ValidateQuoteSalableQtyService $validateQuoteSalableQtyService,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->validateQuoteSalableQtyService = $validateQuoteSalableQtyService;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        if (!$this->canProcessRequest()) {
            return $this->_redirect('checkout/cart', ['_secure' => true]);
        }

        return $this->resultJsonFactory->create()->setData([
            'message' => $this->buildMessage($this->getDaysForPickup()),
        ]);
    }

    private function canProcessRequest(): bool
    {
        /** @var Http $request */
        $request = $this->getRequest();

        return $request->isAjax() && $request->isPost();
    }

    private function getDaysForPickup(): int
    {
        return reduce(function (int $daysForPickup, string $spinCode): int {
            $isSourceSpinCode = $spinCode !== PickAndPayInfo::DEFAULT_PICKUP_LOCATION;
            $allValid = all(function (QuoteItem $item) use ($spinCode) {
                return $this->validateQuoteSalableQtyService->validateItem($item, $spinCode);
            }, $this->getItems());

            if ($allValid && $isSourceSpinCode) {
                return PickAndPayInfo::STANDARD_TYPE_DAYS;
            } elseif ($allValid && !$isSourceSpinCode) {
                return PickAndPayInfo::EXTENDED_TYPE_DAYS;
            }

            return 0;
        }, $this->getSpinCodeList(), 0);
    }

    private function getSpinCodeList(): array
    {
        $quote = $this->checkoutSession->getQuote();

        return $this->validateQuoteSalableQtyService->getSpinCodeList($quote);
    }

    private function getItems(): array
    {
        $quote = $this->checkoutSession->getQuote();

        return $quote->getItems() ?? [];
    }

    private function buildMessage(int $daysForPickup): Phrase
    {
        if ($daysForPickup) {
            return __("It will be possible to pick the products after %1 days from today's date", $daysForPickup);
        }

        return __(first($this->validateQuoteSalableQtyService->getValidationErrors()));
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Wins\Plugin\Quote\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteManagement;
use Nextouch\Wins\Service\Quote\ValidateQuoteSalableQty as ValidateQuoteSalableQtyService;

class ValidateQuoteSalableQty
{
    private ValidateQuoteSalableQtyService $validateQuoteSalableQtyService;

    public function __construct(ValidateQuoteSalableQtyService $validateQuoteSalableQtyService)
    {
        $this->validateQuoteSalableQtyService = $validateQuoteSalableQtyService;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeSubmit(QuoteManagement $subject, Quote $quote, array $orderData = []): array
    {
        try {
            //$this->validateQuoteSalableQtyService->validate($quote);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__('Impossible to place order: %1', $e->getLogMessage()));
        }

        return [$quote, $orderData];
    }
}

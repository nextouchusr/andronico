<?php
declare(strict_types=1);

namespace Nextouch\Wins\Plugin\Findomestic\Controller\Payment;

use Magento\Framework\Exception\LocalizedException;
use Nextouch\Findomestic\Controller\Payment\Redirect;
use Nextouch\Quote\Model\Quote;
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
     */
    public function afterExecute(Redirect $subject, $result)
    {
        try {
            /** @var Quote $quote */
            $quote = $subject->getQuote();

            $this->validateQuoteSalableQtyService->validate($quote);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__('Impossible to create Findomestic application: %1', $e->getLogMessage()));
        }

        return $result;
    }
}

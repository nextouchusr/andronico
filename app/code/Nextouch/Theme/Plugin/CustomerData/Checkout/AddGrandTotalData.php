<?php
declare(strict_types=1);

namespace Nextouch\Theme\Plugin\CustomerData\Checkout;

use Magento\Checkout\CustomerData\Cart;
use Magento\Checkout\Helper\Data as CheckoutHelper;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class AddGrandTotalData
{
    private Session $checkoutSession;
    private CheckoutHelper $checkoutHelper;
    private LoggerInterface $logger;

    public function __construct(
        Session $checkoutSession,
        CheckoutHelper $checkoutHelper,
        LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->checkoutHelper = $checkoutHelper;
        $this->logger = $logger;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetSectionData(Cart $subject, array $result): array
    {
        try {
            $totals = $this->checkoutSession->getQuote()->getTotals();

            if (isset($totals['grand_total'])) {
                $grandTotal = $totals['grand_total'];
                $price = $grandTotal->getValueInclTax() ?: $grandTotal->getValue();

                $result['grand_total'] = $this->checkoutHelper->formatPrice($price);
            }

            return $result;
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());

            return $result;
        }
    }
}

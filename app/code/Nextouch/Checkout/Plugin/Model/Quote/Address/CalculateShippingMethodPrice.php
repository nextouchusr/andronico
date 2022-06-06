<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Model\Quote\Address\RateResult;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Nextouch\Dhl\Model\Carrier\Dhl;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Gls\Model\Carrier\Gls;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Quote\Api\Data\CartItemInterface;
use Nextouch\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use function Lambdish\Phunctional\first;
use function Lambdish\Phunctional\reduce;

class CalculateShippingMethodPrice
{
    private const STANDARD_DELIVERY_DISCOUNT = 0.5;

    private CheckoutSession $checkoutSession;
    private QuoteCollectionFactory $quoteCollectionFactory;
    private PriceCurrencyInterface $priceCurrency;

    public function __construct(
        CheckoutSession $checkoutSession,
        QuoteCollectionFactory $quoteCollectionFactory,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @throws LocalizedException
     */
    public function afterSetPrice(Method $subject, Method $result): Method
    {
        if ($this->shouldCalculatePrice($subject)) {
            $subject->setData('price', $this->priceCurrency->round($this->calculatePrice()));
        }

        return $result;
    }

    private function shouldCalculatePrice(Method $method): bool
    {
        return in_array($method->getData('carrier'), [FastEst::CODE, Dhl::CODE, Gls::CODE]);
    }

    /**
     * @throws LocalizedException
     */
    private function calculatePrice(): float
    {
        $itemWithMostExpensiveShipping = $this->getItemWithMostExpensiveShipping();
        $initial = $itemWithMostExpensiveShipping->getProduct()->getDeliveryPrice();

        return reduce(function (float $acc, CartItemInterface $item) use ($itemWithMostExpensiveShipping) {
            if ($item->getItemId() === $itemWithMostExpensiveShipping->getItemId()) {
                return $acc;
            }

            $deliveryPrice = $item->getProduct()->getDeliveryPrice();
            $deliveryPrice *= self::STANDARD_DELIVERY_DISCOUNT;

            return $acc + $deliveryPrice;
        }, $this->getQuote()->getItems(), $initial);
    }

    /**
     * @throws LocalizedException
     */
    private function getItemWithMostExpensiveShipping(): CartItemInterface
    {
        $itemsSortedByShippingPrice = \Lambdish\Phunctional\sort(function (CartItemInterface $item) {
            return $item->getProduct()->getDeliveryPrice();
        }, $this->getQuote()->getItems());

        return first($itemsSortedByShippingPrice);
    }

    /**
     * @throws LocalizedException
     */
    private function getQuote(): CartInterface
    {
        $quote = $this->checkoutSession->getQuote();

        /** @var CartInterface $result */
        $result = $this->quoteCollectionFactory->create()->getItemById((int) $quote->getId());

        return $result;
    }
}

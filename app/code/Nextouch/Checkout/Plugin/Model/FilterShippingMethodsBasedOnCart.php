<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Model;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Nextouch\Dhl\Model\Carrier\Dhl;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Gls\Model\Carrier\Gls;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Quote\Api\Data\CartItemInterface;
use Nextouch\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use function Lambdish\Phunctional\all;
use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\some;

class FilterShippingMethodsBasedOnCart
{
    private CheckoutSession $checkoutSession;
    private QuoteCollectionFactory $quoteCollectionFactory;

    public function __construct(
        CheckoutSession $checkoutSession,
        QuoteCollectionFactory $quoteCollectionFactory
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
    }

    /**
     * @throws LocalizedException
     */
    public function afterEstimateByAddressId(ShippingMethodManagementInterface $subject, array $result): array
    {
        return reduce(function (array $acc, ShippingMethodInterface $curr) {
            if ($this->isShippableWithInStorePickup() && $curr->getCarrierCode() === 'instore') {
                return [$curr, ...$acc];
            }

            if ($this->isShippableWithFastEst() && $curr->getCarrierCode() === FastEst::CODE) {
                return [$curr, ...$acc];
            }

            if ($this->isShippableWithDhl() && $curr->getCarrierCode() === Dhl::CODE) {
                return [$curr, ...$acc];
            }

            if ($this->isShippableWithGls() && $curr->getCarrierCode() === Gls::CODE) {
                return [$curr, ...$acc];
            }

            return $acc;
        }, $result, []);
    }

    /**
     * @throws LocalizedException
     */
    private function isShippableWithInStorePickup(): bool
    {
        return all(function (CartItemInterface $item) {
            return $item->getProduct()->isPickupable();
        }, $this->getQuote()->getItems());
    }

    /**
     * @throws LocalizedException
     */
    private function isShippableWithFastEst(): bool
    {
        return some(function (CartItemInterface $item) {
            $selectableCarrier = $item->getProduct()->getSelectableCarrier();

            return $selectableCarrier === FastEst::CODE;
        }, $this->getQuote()->getItems());
    }

    /**
     * @throws LocalizedException
     */
    private function isShippableWithDhl(): bool
    {
        $isShippableWithDhl = some(function (CartItemInterface $item) {
            $selectableCarrier = $item->getProduct()->getSelectableCarrier();

            return $selectableCarrier === Dhl::CODE;
        }, $this->getQuote()->getItems());

        return $isShippableWithDhl && !$this->isShippableWithFastEst();
    }

    /**
     * @throws LocalizedException
     */
    private function isShippableWithGls(): bool
    {
        return all(function (CartItemInterface $item) {
            $selectableCarrier = $item->getProduct()->getSelectableCarrier();

            return $selectableCarrier === Gls::CODE;
        }, $this->getQuote()->getItems());
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

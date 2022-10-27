<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Model;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Api\ShipmentEstimationInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Gls\Model\Carrier\Gls;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use function Lambdish\Phunctional\reduce;

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
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterEstimateByAddressId(ShippingMethodManagementInterface $subject, array $result): array
    {
        return $this->filterShippingMethods($result);
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterEstimateByExtendedAddress(ShipmentEstimationInterface $subject, array $result): array
    {
        return $this->filterShippingMethods($result);
    }

    /**
     * @throws LocalizedException
     */
    private function filterShippingMethods(array $shippingMethods): array
    {
        $quote = $this->getQuote();

        return reduce(function (array $acc, ShippingMethodInterface $curr) use ($quote) {
            if ($curr->getCarrierCode() === 'instore' && $quote->isShippableWithInStorePickup()) {
                return [$curr, ...$acc];
            }

            if ($curr->getCarrierCode() === FastEst::CODE && $quote->isShippableWithFastEst()) {
                return [$curr, ...$acc];
            }

            if ($curr->getCarrierCode() === Gls::CODE && $quote->isShippableWithGls()) {
                return [$curr, ...$acc];
            }

            return $acc;
        }, $shippingMethods, []);
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

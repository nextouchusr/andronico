<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Model\Quote\Address\RateResult;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Nextouch\FastEst\Model\Carrier\FastEst;
use Nextouch\Gls\Model\Carrier\Gls;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Quote\Api\Data\CartItemInterface;
use Nextouch\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\sort;

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
        return in_array($method->getData('carrier'), [FastEst::CODE, Gls::CODE]);
    }

    /**
     * @throws LocalizedException
     */
    private function calculatePrice(): float
    {
        $quote = $this->getQuote();

        if ($quote->isShippableWithFastEst()) {
            return $this->calculateFastEstPrice();
        }

        return $this->calculateOtherCarriersPrice();
    }

    /**
     * @throws LocalizedException
     * Nel caso di prodotto spedito da FastEst accoppiato ad un prodotto spedito da GLS la consegna verrà effettuata da FastEst
     * Il costo addebitato al cliente sarà esclusivamente quello relativo alla consegna del prodotto spedito da FastEst
     * (esempio: lavatrice (40€ FastEst) + iphone (7€ GLS) = FastEst a 40€ per il cliente)
     * Anche nel caso di più di un prodotto a carrello GLS il costo sarà sempre solo quello della consegna del prodotto spedito da FastEst
     *
     * Nel caso di carrello multiplo con due o più prodotti spediti da FastEst il costo addebitato al cliente
     * sarà quello del prodotto che presenta un costo di spedizione più alto più il costo degli altri ad uno ad uno scontati (ognuno di essi) al 50%.
     * (esempio: lavatrice (40€) + frigorifero (60€) + forno (40€) = 60€ + 20€ + 20€ quindi un totale di 100€ per l'intera spedizione FastEst
     */
    private function calculateFastEstPrice(): float
    {
        $items = $this->getSortedItems();
        $initial = $items[0]->getProduct()->getDeliveryPrice();
        $itemsForPriceCalculation = array_slice($items, 1);

        return reduce(function (float $acc, CartItemInterface $item) {
            $isShippedByFastEst = $item->getProduct()->getSelectableCarrier() === FastEst::CODE;

            if (!$isShippedByFastEst) {
                return $acc;
            }

            $deliveryPrice = $item->getProduct()->getDeliveryPrice();
            $deliveryPrice *= self::STANDARD_DELIVERY_DISCOUNT;

            return $acc + $deliveryPrice;
        }, $itemsForPriceCalculation, $initial);
    }

    /**
     * @throws LocalizedException
     * Nel caso di carrello multiplo con due o più prodotti spediti da GLS
     * il costo addebitato al cliente sarà quello del prodotto che presenta un costo di spedizione più alto e tutti gli altri gratuiti fino ad un massimo di 3 prodotti a carrello.
     * Oltre i 3 ad ogni prodotto verrà addebitato un costo pari al 50% del suo costo di spedizione originario.
     * (esempio caso1: iphone (7€) + cavetto (2€) + cover telefono (2€) = totale costo spedizione 7€).
     * (esempio caso2: iphone (7€) + cavetto (2€) + cover telefono (2€) + cuffiette (6€) + cassa audio (8€) = totale costo spedizione 10€ (cassa audio + 50% cavetto + 50% cover telefono)
     */
    private function calculateOtherCarriersPrice(): float
    {
        $items = $this->getSortedItems();
        $initial = $items[0]->getProduct()->getDeliveryPrice();
        $itemsForPriceCalculation = array_slice($items, 3);
        
        return reduce(function (float $acc, CartItemInterface $item) {
            $deliveryPrice = $item->getProduct()->getDeliveryPrice();
            $deliveryPrice *= self::STANDARD_DELIVERY_DISCOUNT;

            return $acc + $deliveryPrice;
        }, $itemsForPriceCalculation, $initial);
    }

    /**
     * @return CartItemInterface[]
     * @throws LocalizedException
     */
    private function getSortedItems(): array
    {
        $initial = ['fast_est' => [], 'others' => []];

        $itemsSortedByPrice = sort(function (CartItemInterface $a, CartItemInterface $b) {
            return $b->getProduct()->getDeliveryPrice() - $a->getProduct()->getDeliveryPrice();
        }, $this->getQuote()->getItems());

        $itemsGroupedByCarrier = reduce(function (array $acc, CartItemInterface $item) {
            $isShippedByFastEst = $item->getProduct()->getSelectableCarrier() === FastEst::CODE;
            $key = $isShippedByFastEst ? 'fast_est' : 'others';
            $startIndex = count($acc[$key]);
            $count = (int) $item->getQty();

            $acc[$key] = array_merge($acc[$key], array_fill($startIndex, $count, $item));

            return $acc;
        }, $itemsSortedByPrice, $initial);

        return [...$itemsGroupedByCarrier['fast_est'], ...$itemsGroupedByCarrier['others']];
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

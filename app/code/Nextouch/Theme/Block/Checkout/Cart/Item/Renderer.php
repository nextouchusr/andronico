<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Checkout\Cart\Item;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Catalog\Pricing\Price\RegularPrice;
use Magento\Checkout\Model\Session;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Module\Manager;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Message\InterpretationStrategyInterface;
use Magento\Framework\View\Element\Template\Context;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;

class Renderer extends \Magento\Checkout\Block\Cart\Item\Renderer
{
    private const SERVICE_SEPARATOR = ',';

    private PostHelper $postHelper;

    public function __construct(
        Context $context,
        Configuration $productConfig,
        Session $checkoutSession,
        ImageBuilder $imageBuilder,
        Data $urlHelper,
        ManagerInterface $messageManager,
        PriceCurrencyInterface $priceCurrency,
        Manager $moduleManager,
        InterpretationStrategyInterface $messageInterpretationStrategy,
        PostHelper $postHelper,
        array $data = [],
        ItemResolverInterface $itemResolver = null
    ) {
        parent::__construct(
            $context,
            $productConfig,
            $checkoutSession,
            $imageBuilder,
            $urlHelper,
            $messageManager,
            $priceCurrency,
            $moduleManager,
            $messageInterpretationStrategy,
            $data,
            $itemResolver
        );
        $this->postHelper = $postHelper;
    }

    public function getRemoveServicePostDataParams(array $service): string
    {
        return $this->postHelper->getPostData(
            'removeQuoteItemService',
            array_merge($service, ['itemId' => $this->getItem()->getId()])
        );
    }

    public function getPriceType($priceCode): PriceInterface
    {
        return $this->getProduct()->getPriceInfo()->getPrice($priceCode);
    }

    public function hasSpecialPrice(): bool
    {
        $displayRegularPrice = $this->getPriceType(RegularPrice::PRICE_CODE)->getAmount()->getValue();
        $displayFinalPrice = $this->getPriceType(FinalPrice::PRICE_CODE)->getAmount()->getValue();

        return $displayFinalPrice < $displayRegularPrice;
    }

    public function renderAmount(string $priceType, array $arguments = []): string
    {
        try {
            $arguments = array_replace($this->getData(), $arguments);
            $priceRender = $this->getLayout()->getBlock('product.price.render.default');

            return $priceRender->render($priceType, $this->getProduct(), $arguments);
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function getFormattedServiceList(): array
    {
        return reduce(function ($acc, $option) {
            $serviceIds = $this->getServiceIds($option);
            $serviceLabels = $this->getServiceLabels($option);

            $services = map(function ($serviceId, $index) use ($option, $serviceLabels) {
                return [
                    'optionId' => $option['option_id'],
                    'serviceId' => $serviceId,
                    'serviceLabel' => $serviceLabels[$index],
                ];
            }, $serviceIds);

            return array_merge($acc, $services);
        }, $this->getOptionList(), []);
    }

    private function getServiceIds(array $option): array
    {
        $item = $this->getItem();
        $option = $item->getOptionByCode('option_' . $option['option_id']);

        if (!$option) {
            return [];
        }

        return explode(self::SERVICE_SEPARATOR, $option->getValue());
    }

    private function getServiceLabels(array $optionValue): array
    {
        return explode(self::SERVICE_SEPARATOR, parent::getFormatedOptionValue($optionValue)['value']);
    }
}

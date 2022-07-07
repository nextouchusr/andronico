<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Checkout\Cart;

use Amasty\Shopby\Model\Layer\Filter\OnSale\Helper as OnSaleHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\LinkFactory;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Helper\Stock;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\TargetRule\Block\Checkout\Cart\Crosssell;
use Magento\TargetRule\Helper\Data;
use Magento\TargetRule\Model\IndexFactory;
use Magento\TargetRule\Model\ResourceModel\Index;
use Magento\TargetRule\Model\Rotation;
use function Lambdish\Phunctional\flat_map;

class Offers extends Crosssell
{
    private const MAX_OFFERS_TO_DISPLAY = 10;

    private OnSaleHelper $onSaleHelper;

    public function __construct(
        Context $context,
        Index $index,
        Data $targetRuleData,
        CollectionFactory $productCollectionFactory,
        Visibility $visibility,
        Stock $stockHelper,
        Session $session,
        LinkFactory $productLinkFactory,
        IndexFactory $indexFactory,
        ConfigInterface $productTypeConfig,
        ProductRepositoryInterface $productRepository,
        OnSaleHelper $onSaleHelper,
        array $data = [],
        Rotation $rotation = null
    ) {
        parent::__construct(
            $context,
            $index,
            $targetRuleData,
            $productCollectionFactory,
            $visibility,
            $stockHelper,
            $session,
            $productLinkFactory,
            $indexFactory,
            $productTypeConfig,
            $productRepository,
            $data,
            $rotation
        );
        $this->onSaleHelper = $onSaleHelper;
    }

    public function getTitle(): Phrase
    {
        if ($this->hasCombinedOffers()) {
            return __('Discover the combined offers');
        }

        return __('Offers for you');
    }

    public function hasCombinedOffers(): bool
    {
        return count($this->getCombinedOffersItemCollection()) > 0;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getItemCollection(): array
    {
        if ($this->hasCombinedOffers()) {
            $items = $this->getCombinedOffersItemCollection();
        } else {
            $items = $this->getRandomOffersItemCollection();
        }

        return array_slice($items, 0, self::MAX_OFFERS_TO_DISPLAY);
    }

    private function getCombinedOffersItemCollection(): array
    {
        return flat_map(function (Product $product) {
            $itemCollection = $this->_productCollectionFactory->create();
            $attributes = $this->_catalogConfig->getProductAttributes();

            $itemCollection
                ->addAttributeToSelect($attributes)
                ->addIdFilter($product->getId())
                ->addStoreFilter()
                ->setVisibility($this->_visibility->getVisibleInCatalogIds());

            $this->onSaleHelper->addOnSaleFilter($itemCollection);

            $itemCollection->load();
            $itemCollection->setDataToAll('related_product', $product);

            return $itemCollection->getItems();
        }, parent::getItemCollection());
    }

    /**
     * @throws NoSuchEntityException
     */
    private function getRandomOffersItemCollection(): array
    {
        $itemCollection = $this->_productCollectionFactory->create();
        $attributes = $this->_catalogConfig->getProductAttributes();

        $itemCollection
            ->addAttributeToSelect($attributes)
            ->addStoreFilter()
            ->setVisibility($this->_visibility->getVisibleInCatalogIds());

        $this->onSaleHelper->addOnSaleFilter($itemCollection);

        $itemCollection->load();

        return $itemCollection->getItems();
    }
}

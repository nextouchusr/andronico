<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order\Item;

use Magento\Catalog\Api\Data\CustomOptionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemInterfaceFactory;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;

class CustomOptionsProcessor
{
    private const OPTION_SEPARATOR = ',';

    private OrderItemInterfaceFactory $orderItemInterfaceFactory;
    private OrderItemExtensionFactory $orderItemExtensionFactory;

    public function __construct(
        OrderItemInterfaceFactory $orderItemInterfaceFactory,
        OrderItemExtensionFactory $orderItemExtensionFactory
    ) {
        $this->orderItemInterfaceFactory = $orderItemInterfaceFactory;
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
    }

    /**
     * @return OrderItemInterface[]
     */
    public function getAllCustomOptions(OrderInterface $order): array
    {
        return reduce(function (array $acc, OrderItemInterface $item) {
            $productOption = $item->getProductOption();

            if (!$productOption) {
                return $acc;
            }

            $extensionAttributes = $productOption->getExtensionAttributes();
            $customOptions = $extensionAttributes ? $extensionAttributes->getCustomOptions() : [];

            return $this->transformOptionsToOrderItems((int) $item->getItemId(), $customOptions);
        }, $order->getItems(), []);
    }

    /**
     * @return OrderItemInterface[]
     */
    private function transformOptionsToOrderItems(int $referenceItemId, array $customOptions): array
    {
        return reduce(function (array $acc, CustomOptionInterface $item) use ($referenceItemId) {
            return array_merge($acc, $this->transformOptionToOrderItem($referenceItemId, $item));
        }, $customOptions, []);
    }

    /**
     * @return OrderItemInterface[]
     */
    private function transformOptionToOrderItem(int $referenceItemId, CustomOptionInterface $customOption): array
    {
        return map(function (array $option) use ($referenceItemId) {
            $orderItem = $this->orderItemInterfaceFactory->create()
                ->setItemId(0)
                ->setSku((string) $option['sku'])
                ->setName((string) $option['title'])
                ->setBasePriceInclTax((float) $option['price'])
                ->setQtyOrdered(1);

            $extensionAttributes = $orderItem->getExtensionAttributes() ?: $this->orderItemExtensionFactory->create();
            $extensionAttributes->setReferenceItemId($referenceItemId);

            return $orderItem;
        }, $this->mergeCustomOptionExtraInfo($customOption));
    }

    private function mergeCustomOptionExtraInfo(CustomOptionInterface $customOption): array
    {
        $extensionAttributes = $customOption->getExtensionAttributes();

        $optionValues = explode(self::OPTION_SEPARATOR, $customOption->getOptionValue());
        $optionSkus = explode(self::OPTION_SEPARATOR, $extensionAttributes->getOptionSku());
        $optionTitles = explode(self::OPTION_SEPARATOR, $extensionAttributes->getOptionTitle());
        $optionPrices = explode(self::OPTION_SEPARATOR, $extensionAttributes->getOptionPrice());

        return array_map(function (int $index) use ($optionSkus, $optionTitles, $optionPrices) {
            return [
                'sku' => $optionSkus[$index],
                'title' => $optionTitles[$index],
                'price' => $optionPrices[$index],
            ];
        }, array_keys($optionValues));
    }
}

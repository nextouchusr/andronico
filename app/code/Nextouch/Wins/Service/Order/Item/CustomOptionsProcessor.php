<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order\Item;

use Magento\Catalog\Api\Data\CustomOptionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemInterfaceFactory;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;

class CustomOptionsProcessor
{
    private const OPTION_SEPARATOR = ',';

    private OrderItemInterfaceFactory $orderItemInterfaceFactory;

    public function __construct(OrderItemInterfaceFactory $orderItemInterfaceFactory)
    {
        $this->orderItemInterfaceFactory = $orderItemInterfaceFactory;
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

            return $this->transformOptionsToOrderItems($customOptions);
        }, $order->getItems(), []);
    }

    /**
     * @return OrderItemInterface[]
     */
    private function transformOptionsToOrderItems(array $customOptions): array
    {
        return reduce(function (array $acc, CustomOptionInterface $item) {
            return array_merge($acc, $this->transformOptionToOrderItem($item));
        }, $customOptions, []);
    }

    /**
     * @return OrderItemInterface[]
     */
    private function transformOptionToOrderItem(CustomOptionInterface $customOption): array
    {
        $extensionAttributes = $customOption->getExtensionAttributes();

        $optionValues = explode(self::OPTION_SEPARATOR, $customOption->getOptionValue());
        $optionSkus = explode(self::OPTION_SEPARATOR, $extensionAttributes->getOptionSku());
        $optionTitles = explode(self::OPTION_SEPARATOR, $extensionAttributes->getOptionTitle());
        $optionPrices = explode(self::OPTION_SEPARATOR, $extensionAttributes->getOptionPrice());

        return map(function (string $optionTypeId, int $index) use ($optionSkus, $optionTitles, $optionPrices) {
            return $this->orderItemInterfaceFactory->create()
                ->setItemId(0)
                ->setSku((string) $optionSkus[$index])
                ->setName((string) $optionTitles[$index])
                ->setBasePriceInclTax((float) $optionPrices[$index])
                ->setQtyOrdered(1);
        }, $optionValues);
    }
}

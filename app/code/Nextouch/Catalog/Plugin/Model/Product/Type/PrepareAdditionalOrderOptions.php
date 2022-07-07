<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Plugin\Model\Product\Type;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Catalog\Api\CustomOptionExtraInfoProcessorInterface;
use function Lambdish\Phunctional\reduce;

class PrepareAdditionalOrderOptions
{
    private CustomOptionExtraInfoProcessorInterface $processor;

    public function __construct(CustomOptionExtraInfoProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetOrderOptions(AbstractType $subject, array $result, Product $product): array
    {
        $result['options'] = $this->getProductOptions($product);

        return $result;
    }

    /**
     * @throws LocalizedException
     */
    private function getProductOptions(Product $product): array
    {
        $optionIds = $product->getCustomOption('option_ids');

        if (!$optionIds) {
            return [];
        }

        return reduce(function (array $acc, string $optionId) use ($product) {
            $option = $product->getOptionById($optionId);

            if (!$option) {
                return $acc;
            }

            $customOption = $product->getCustomOption(AbstractType::OPTION_PREFIX . $option->getId());
            $customOptionExtraInfo = $this->processor->addCustomOptionExtraInfo($customOption);

            $group = $option->groupFactory($option->getType())
                ->setOption($option)
                ->setProduct($product)
                ->setConfigurationItemOption($customOptionExtraInfo);

            $acc[] = [
                'label' => $option->getTitle(),
                'value' => $group->getFormattedOptionValue($customOptionExtraInfo->getValue()),
                'print_value' => $group->getPrintableOptionValue($customOptionExtraInfo->getValue()),
                'option_id' => $option->getId(),
                'option_type' => $option->getType(),
                'option_value' => $customOptionExtraInfo->getValue(),
                'option_sku' => $customOptionExtraInfo->getSku(),
                'option_title' => $group->getFormattedOptionValue($customOptionExtraInfo->getValue()),
                'option_price' => $customOptionExtraInfo->getPrice(),
                'custom_view' => $group->isCustomizedView(),
            ];

            return $acc;
        }, explode(',', $optionIds->getValue()), []);
    }
}

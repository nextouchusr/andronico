<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Plugin\Helper\Product;

use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Nextouch\Catalog\Api\CustomOptionExtraInfoProcessorInterface;
use function Lambdish\Phunctional\map;

class AddProductSkuToOptions
{
    private CustomOptionExtraInfoProcessorInterface $processor;

    public function __construct(CustomOptionExtraInfoProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetCustomOptions(Configuration $subject, array $options, ItemInterface $item): array
    {
        return map(function (array $option) use ($item) {
            $itemOption = $item->getOptionByCode('option_' . $option['option_id']);
            $customOptionExtraInfo = $this->processor->addCustomOptionExtraInfo($itemOption);

            return array_merge($option, ['sku' => $customOptionExtraInfo->getSku()]);
        }, $options);
    }
}

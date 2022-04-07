<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Magento\Catalog\Api\Data\CustomOptionExtensionFactory;
use Magento\Catalog\Api\Data\CustomOptionInterface;
use Magento\Catalog\Model\ResourceModel\Product\Option\Value\CollectionFactory as OptionCollectionFactory;
use Nextouch\Catalog\Api\CustomOptionsExtraInfoProcessorInterface;
use function Lambdish\Phunctional\map;

class CustomOptionsExtraInfoProcessor implements CustomOptionsExtraInfoProcessorInterface
{
    private const OPTION_SEPARATOR = ',';

    private OptionCollectionFactory $optionCollectionFactory;
    private CustomOptionExtensionFactory $optionExtensionFactory;

    public function __construct(
        OptionCollectionFactory $optionCollectionFactory,
        CustomOptionExtensionFactory $optionExtensionFactory
    ) {
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->optionExtensionFactory = $optionExtensionFactory;
    }

    public function addCustomOptionsExtraInfo(array $customOptions): array
    {
        return map(fn(CustomOptionInterface $item) => $this->addCustomOptionExtraInfo($item), $customOptions);
    }

    private function addCustomOptionExtraInfo(CustomOptionInterface $customOption): CustomOptionInterface
    {
        $optionExtraInfo = $this->fetchCustomOptionExtraInfo($customOption);
        $extensionAttributes = $customOption->getExtensionAttributes() ?: $this->optionExtensionFactory->create();
        $setOptionData = function (string $col, string $key) use ($optionExtraInfo, $extensionAttributes) {
            $values = array_column($optionExtraInfo, $col);
            $value = implode(self::OPTION_SEPARATOR, $values);
            $extensionAttributes->setData($key, $value);
        };

        $setOptionData('sku', 'option_sku');
        $setOptionData('title', 'option_title');
        $setOptionData('price', 'option_price');

        $customOption->setExtensionAttributes($extensionAttributes);

        return $customOption;
    }

    private function fetchCustomOptionExtraInfo(CustomOptionInterface $customOption): array
    {
        $optionCollection = $this->optionCollectionFactory->create();
        $optionValues = explode(self::OPTION_SEPARATOR, $customOption->getOptionValue());

        return $optionCollection
            ->addFieldToSelect('option_id')
            ->addFieldToSelect('option_type_id')
            ->addFieldToSelect('sku')
            ->getSelect()
            ->joinInner(
                ['t' => $optionCollection->getTable('catalog_product_option_type_title')],
                'main_table.option_type_id = t.option_type_id',
                ['t.title']
            )
            ->joinInner(
                ['p' => $optionCollection->getTable('catalog_product_option_type_price')],
                'main_table.option_type_id = p.option_type_id',
                ['p.price']
            )
            ->where('main_table.option_type_id in (?)', $optionValues)
            ->query()
            ->fetchAll();
    }
}

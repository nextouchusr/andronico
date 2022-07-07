<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Magento\Catalog\Model\Product\Configuration\Item\Option\OptionInterface;
use Magento\Catalog\Model\ResourceModel\Product\Option\Value\CollectionFactory as OptionCollectionFactory;
use Nextouch\Catalog\Api\CustomOptionExtraInfoProcessorInterface;

class CustomOptionExtraInfoProcessor implements CustomOptionExtraInfoProcessorInterface
{
    private const OPTION_SEPARATOR = ',';

    private OptionCollectionFactory $optionCollectionFactory;

    public function __construct(OptionCollectionFactory $optionCollectionFactory)
    {
        $this->optionCollectionFactory = $optionCollectionFactory;
    }

    public function addCustomOptionExtraInfo(OptionInterface $customOption): OptionInterface
    {
        $optionExtraInfo = $this->fetchCustomOptionExtraInfo($customOption);
        $setOptionData = function (string $key) use ($optionExtraInfo, $customOption) {
            $values = array_column($optionExtraInfo, $key);
            $value = implode(self::OPTION_SEPARATOR, $values);
            $customOption->setData($key, $value);
        };

        $setOptionData('sku');
        $setOptionData('title');
        $setOptionData('price');

        return $customOption;
    }

    private function fetchCustomOptionExtraInfo(OptionInterface $customOption): array
    {
        $optionCollection = $this->optionCollectionFactory->create();
        $optionValues = explode(self::OPTION_SEPARATOR, $customOption->getValue());

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

<?php
declare(strict_types=1);

namespace Nextouch\Theme\Plugin\Block\Catalog\Product\View;

use Amasty\Shopby\Helper\FilterSetting as FilterHelper;
use Magento\Catalog\Block\Product\View\Attributes as ProductViewAttributes;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class AppendAttributeUnitsLabel
{
    public const CATEGORY_IDS_ATTRIBUTE_NAME = 'category_ids';

    private FilterHelper $filterHelper;
    private Attribute $eav;
    private PriceCurrencyInterface $currency;

    public function __construct(
        FilterHelper $filterSetting,
        Attribute $attribute,
        PriceCurrencyInterface $currency
    ) {
        $this->filterHelper = $filterSetting;
        $this->eav = $attribute;
        $this->currency = $currency;
    }

    public function afterGetAdditionalData(ProductViewAttributes $subject, $data): array
    {
        $priceAttributeCodes = $this->eav->getAttributeCodesByFrontendType('price');

        foreach ($data as &$attributeGroup) {
            foreach ($attributeGroup['attributes'] as &$row) {
                if (in_array($row['code'], $priceAttributeCodes)) {
                    $setting = $this->filterHelper->getFilterSettingByCode($row['code']);
                    if (!$setting->getUnitsLabelUseCurrencySymbol()) {
                        $row['value'] = preg_replace('@<[^>]+>@u', '', $row['value']);
                        $pattern = '@\s*' . preg_quote($this->currency->getCurrencySymbol(), '@') . '\s*@u';
                        $row['value'] = preg_replace($pattern, '', $row['value']);
                        $row['value'] .= ' ' . $setting->getUnitsLabel();
                    }
                }
            }
        }

        return $data;
    }
}

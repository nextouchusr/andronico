<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Product\Attribute;

use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Nextouch\ImportExport\Model\Wins\Property;

class SelectableAttributeMapper extends AbstractAttributeMapper
{
    public function map(Property $property): ProductAttributeInterface
    {
        $attribute = $this->attributeFactory->create();

        $attribute->setAttributeCode($property->getCode());
        $attribute->setBackendType('int');
        $attribute->setDefaultFrontendLabel($property->getDescription());
        $attribute->setFrontendInput('select');
        $attribute->setIsRequired(false);
        $attribute->setScope(EavAttributeInterface::SCOPE_STORE_TEXT);
        $attribute->setIsUserDefined(true);
        $attribute->setIsUsedInGrid(true);
        $attribute->setIsFilterableInGrid(true);
        $attribute->setIsVisible(true);
        $attribute->setIsComparable(true);
        $attribute->setIsFilterable(true);
        $attribute->setIsVisibleOnFront(true);
        $attribute->setIsHtmlAllowedOnFront(true);

        return $attribute;
    }
}

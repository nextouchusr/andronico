<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Product\Attribute;

use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Nextouch\ImportExport\Model\Wins\Property;

class NumericAttributeMapper extends AbstractAttributeMapper
{
    public function map(Property $property): ProductAttributeInterface
    {
        $attribute = $this->attributeFactory->create();

        $attribute->setAttributeCode($property->getCode());
        $attribute->setBackendType('decimal');
        $attribute->setDefaultFrontendLabel($property->getDescription());
        $attribute->setFrontendInput('price');
        $attribute->setIsRequired(false);
        $attribute->setScope(EavAttributeInterface::SCOPE_STORE_TEXT);
        $attribute->setIsUserDefined(true);
        $attribute->setIsVisible(true);
        $attribute->setIsVisibleOnFront(true);

        return $attribute;
    }
}

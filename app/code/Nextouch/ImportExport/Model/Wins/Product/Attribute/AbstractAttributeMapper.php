<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Product\Attribute;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterfaceFactory;
use Nextouch\ImportExport\Model\Wins\Property;

abstract class AbstractAttributeMapper
{
    protected ProductAttributeInterfaceFactory $attributeFactory;

    public function __construct(ProductAttributeInterfaceFactory $attributeFactory)
    {
        $this->attributeFactory = $attributeFactory;
    }

    abstract public function map(Property $property): ProductAttributeInterface;
}

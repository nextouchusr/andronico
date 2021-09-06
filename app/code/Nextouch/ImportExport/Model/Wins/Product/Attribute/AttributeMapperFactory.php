<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Product\Attribute;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Nextouch\ImportExport\Model\Wins\Property;
use Nextouch\ImportExport\Model\Wins\Property\NumericProperty;
use Nextouch\ImportExport\Model\Wins\Property\SelectableProperty;
use Nextouch\ImportExport\Model\Wins\Property\TextualProperty;

class AttributeMapperFactory
{
    private ObjectManagerInterface $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @throws LocalizedException
     */
    public function create(Property $property): AbstractAttributeMapper
    {
        switch (true) {
            case $property instanceof NumericProperty:
                return $this->objectManager->create(NumericAttributeMapper::class);
            case $property instanceof TextualProperty:
                return $this->objectManager->create(TextualAttributeMapper::class);
            case $property instanceof SelectableProperty:
                return $this->objectManager->create(SelectableAttributeMapper::class);
            default:
                throw new LocalizedException(__('Unknown Wins property instance %1.', get_class($property)));
        }
    }
}

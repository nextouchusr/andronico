<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Block\Catalog\Adminhtml\Product\Attribute;

class Grid extends \Magento\Catalog\Block\Adminhtml\Product\Attribute\Grid
{
    protected function _prepareMassaction(): self
    {
        $this->setMassactionIdField('attribute_code');
        $this->setMassactionIdFilter('attribute_code');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('attribute_codes');
        $this->addMassactionBlocks();

        return $this;
    }

    private function addMassactionBlocks(): void
    {
        $this->getMassactionBlock()->addItem('delete', [
                'label' => __('Delete'),
                'url' => $this->getUrl('catalog/product_attribute/massDelete'),
                'confirm' => __('Delete selected items?'),
            ]
        );

        $this->getMassactionBlock()->addItem('enable_filterable', [
                'label' => __('Enable Filterable Option'),
                'url' => $this->getUrl('catalog/product_attribute/massEnableFilterable'),
                'confirm' => __('Update selected items?'),
            ]
        );

        $this->getMassactionBlock()->addItem('disable_filterable', [
                'label' => __('Disable Filterable Option'),
                'url' => $this->getUrl('catalog/product_attribute/massDisableFilterable'),
                'confirm' => __('Update selected items?'),
            ]
        );
    }
}

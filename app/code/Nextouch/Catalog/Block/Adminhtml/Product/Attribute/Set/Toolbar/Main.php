<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Block\Adminhtml\Product\Attribute\Set\Toolbar;

use Magento\Backend\Block\Widget\Button;

class Main extends \Magento\Catalog\Block\Adminhtml\Product\Attribute\Set\Toolbar\Main
{
    protected function _prepareLayout(): self
    {
        $this->getToolbar()->addChild(
            'clearButton',
            Button::class,
            [
                'label' => __('Clear All'),
                'onclick' => 'setLocation(\'' . $this->getUrl('catalog/*/clear') . '\')',
                'class' => 'clear primary clear-all',
            ]
        );

        return parent::_prepareLayout();
    }
}

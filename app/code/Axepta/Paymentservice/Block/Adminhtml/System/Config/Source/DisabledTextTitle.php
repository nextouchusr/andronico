<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Block\Adminhtml\System\Config\Source;

use Magento\Config\Block\System\Config\Form\Field;

class DisabledTextTitle extends Field
{
    const DISABLED_TEXT_TITLE = 'system/config/disabled_text_title.phtml';


    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::DISABLED_TEXT_TITLE);
        }
        return $this;
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }
}

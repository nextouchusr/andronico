<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Block\Adminhtml\System\Config\Source;

use Magento\Config\Block\System\Config\Form\Field;

class URLConfig extends Field
{
    const TEMPLATE = 'system/config/url_config.phtml';


    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::TEMPLATE);
        }
        return $this;
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }
}

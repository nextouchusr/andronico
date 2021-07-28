<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 25.10.18
 * Time: 16:33
 */

namespace Axepta\Paymentservice\Block\Adminhtml\System\Config\Source;

use Magento\Config\Block\System\Config\Form\Field;

class LogDateRangeTo extends Field
{
    const CUSTOM_HTML_FIELD = 'system/config/logdaterangeto.phtml';

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::CUSTOM_HTML_FIELD);
        }
        return $this;
    }
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->setDateFormat(\Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT);
        $element->setTimeFormat(null);
        return parent::render($element);
    }
}

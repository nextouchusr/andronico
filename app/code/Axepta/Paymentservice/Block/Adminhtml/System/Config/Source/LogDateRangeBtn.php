<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 25.10.18
 * Time: 16:34
 */

namespace Axepta\Paymentservice\Block\Adminhtml\System\Config\Source;

use Magento\Config\Block\System\Config\Form\Field;

class LogDateRangeBtn extends Field
{
    const MULTI_CURRENCY_TABLE_TEMPLATE = 'system/config/logdaterangebtn.phtml';


    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::MULTI_CURRENCY_TABLE_TEMPLATE);
        }
        return $this;
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }
}

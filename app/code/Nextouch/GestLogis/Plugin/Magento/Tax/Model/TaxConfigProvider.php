<?php


namespace Nextouch\GestLogis\Plugin\Magento\Tax\Model;

use Nextouch\GestLogis\Helper\Data as DataHelper;

class TaxConfigProvider
{

    /**
     * @var DataHelper
     */
    private $_dataHelper;

    /**
     * @param DataHelper $dataHelper
     */
    public function __construct(
        DataHelper $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\Tax\Model\TaxConfigProvider $subject
     * @param $result
     * @return mixed
     */
    public function afterGetConfig(
        \Magento\Tax\Model\TaxConfigProvider $subject,
        $result
    ) {
        if($this->_dataHelper->isShippingActive()){
            $result['defaultPostcode'] = $this->_dataHelper->getPostcodeFromSession();
        }

        return $result;
    }
}
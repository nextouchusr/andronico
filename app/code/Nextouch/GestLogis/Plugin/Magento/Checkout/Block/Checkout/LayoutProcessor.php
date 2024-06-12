<?php


namespace Nextouch\GestLogis\Plugin\Magento\Checkout\Block\Checkout;

use Nextouch\GestLogis\Helper\Data as DataHelper;

class LayoutProcessor
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
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param $result
     * @return mixed
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, $result)
    {
        if($this->_dataHelper->isShippingActive()){

            if(isset($result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode'])){
    
                $result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']['value'] = $this->_dataHelper->getPostcodeFromSession();
            }
        }
        

        return $result;
    }
}
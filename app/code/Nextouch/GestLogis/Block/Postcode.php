<?php

namespace Nextouch\GestLogis\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Magento\Framework\Registry;

class Postcode extends Template
{
    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * __construct
     *
     * @param Context $context
     * @param DataHelper $dataHelper
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        DataHelper $dataHelper,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_dataHelper = $dataHelper;
        $this->_registry   = $registry;
    }

    /**
     * GetSaveShippingPriceAjaxUrl
     *
     * @return string
     */
    public function getSaveShippingPriceAjaxUrl()
    {
        return $this->getUrl('gestlogis/postcode/save');
    }

    /**
     * DataHelperObj
     *
     * @return DataHelper
     */
    public function dataHelperObj()
    {
        return $this->_dataHelper;
    }

    /**
     * GetCurrentProduct
     *
     * @return object|null
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * GetAttributeSetId
     *
     * @return int
     */
    public function getAttributeSetId()
    {
        $currentProduct = $this->getCurrentProduct();
        if ($currentProduct && $currentProduct->getAttributeSetId()) {
            return (int) $currentProduct->getAttributeSetId();
        }

        return 0;
    }

    /**
     * GetProductId
     *
     * @return int
     */
    public function getProductId()
    {
        try {
            $currentProduct = $this->getCurrentProduct();
            if ($currentProduct && $currentProduct->getId()) {
                return (int) $currentProduct->getId();
            }
        } catch (\Exception $e) {
            // Do Nothing.
        }

        return 0;
    }
}

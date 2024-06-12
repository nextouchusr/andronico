<?php

namespace Elightwalk\AttributeGroup\Plugin\Block\Adminhtml\Product\Attribute\Edit\Tab;

use Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Front as MagentoCatalogFront;
use Magento\Framework\Registry;

class Front
{
    public const ATTRIBUTE_GROUP_LABEL = "attribute_group_label";

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * __construct
     *
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    ) {
        $this->_registry = $registry;
    }

    /**
     * AroundGetFormHtml
     *
     * @param MagentoCatalogFront $subject
     * @param \Closure $proceed
     * @return void
     */
    public function aroundGetFormHtml(
        MagentoCatalogFront $subject,
        \Closure $proceed
    ) {
        $attributeObject     = $this->_registry->registry('entity_attribute');
        $attributeGroupLabel = $attributeObject->getData('attribute_group_label');

        $form     = $subject->getForm();
        $fieldset = $form->getElement('front_fieldset');

        $fieldset->addField(
            self::ATTRIBUTE_GROUP_LABEL,
            'text',
            [
                'name'  => self::ATTRIBUTE_GROUP_LABEL,
                'label' => __('Group Label'),
                'title' => __('Group Label'),
                "note"  => "Whatever you added group label here is responsible for to group all the attributes on frontend.",
                'value' => $attributeGroupLabel
            ]
        );

        return $proceed();
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Theme\Plugin\Block\Adminhtml\Catalog\Product\Attribute\Edit\Tab\Advanced;

use Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Advanced;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Registry;

class AddAttributeExplanationField
{
    private const FIELD_KEY = 'attribute_explanation';

    private Config $wysiwygConfig;
    private Registry $coreRegistry;

    public function __construct(Config $wysiwygConfig, Registry $coreRegistry)
    {
        $this->wysiwygConfig = $wysiwygConfig;
        $this->coreRegistry = $coreRegistry;
    }

    public function beforeGetFormHtml(Advanced $subject): void
    {
        $form = $subject->getForm();
        $fieldset = $form->getElement('advanced_fieldset');
        $this->addField($fieldset);
    }

    private function addField(AbstractElement $fieldset): void
    {
        $attributeObject = $this->getAttributeObject();

        $fieldset->addField(
            self::FIELD_KEY,
            'editor',
            [
                'name' => self::FIELD_KEY,
                'value' => $attributeObject->getData(self::FIELD_KEY),
                'label' => __('Attribute Explanation'),
                'title' => __('Attribute Explanation'),
                'wysiwyg' => true,
                'config' => $this->wysiwygConfig->getConfig(['add_variables' => false]),
            ],
            Attribute::ATTRIBUTE_CODE
        );
    }

    private function getAttributeObject(): Attribute
    {
        return $this->coreRegistry->registry('entity_attribute');
    }
}

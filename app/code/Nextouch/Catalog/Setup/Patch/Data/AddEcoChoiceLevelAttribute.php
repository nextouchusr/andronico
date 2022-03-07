<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Setup\Patch\Data;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Nextouch\Catalog\Api\Data\ProductInterface;

class AddEcoChoiceLevelAttribute implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;
    private EavSetupFactory $eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    /**
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function apply(): self
    {
        $productSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $productSetup->addAttribute(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            ProductInterface::ECO_CHOICE_LEVEL,
            [
                'group' => 'Content',
                'type' => 'int',
                'label' => 'Eco Choice Level',
                'input' => 'text',
                'required' => false,
                'sort_order' => 20,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'user_defined' => true,
                'visible' => true,
                'validate_rules' => '{"input_validation":"numeric"}',
            ]
        );

        return $this;
    }
}

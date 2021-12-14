<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Setup\Patch\Data;

use Magento\Catalog\Api\Data\CategoryAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Nextouch\Catalog\Api\Data\CategoryInterface;

class AddExternalCategoryIdAttribute implements DataPatchInterface
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
        $categorySetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $categorySetup->addAttribute(
            CategoryAttributeInterface::ENTITY_TYPE_CODE,
            CategoryInterface::EXTERNAL_CATEGORY_ID,
            [
                'type' => 'varchar',
                'label' => 'External Category ID',
                'input' => 'text',
                'visible' => true,
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'General Information',
            ]
        );

        return $this;
    }
}

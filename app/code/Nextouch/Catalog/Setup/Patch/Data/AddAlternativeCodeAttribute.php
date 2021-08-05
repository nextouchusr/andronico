<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Nextouch\Catalog\Model\Product\ProductAttributeEnum;
use Zend_Validate_Exception;

class AddAlternativeCodeAttribute implements DataPatchInterface
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
     * @throws Zend_Validate_Exception
     */
    public function apply(): self
    {
        $productSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $productSetup->addAttribute(
            Product::ENTITY,
            ProductAttributeEnum::ALTERNATIVE_CODE,
            [
                'group' => 'Product Details',
                'type' => 'varchar',
                'label' => 'Alternative Code',
                'input' => 'text',
                'required' => false,
                'sort_order' => 25,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
            ]
        );

        return $this;
    }
}

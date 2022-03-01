<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Setup\Patch\Data;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Customer\Model\Attribute\Backend\Data\Boolean;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Nextouch\Catalog\Api\Data\ProductInterface;

class AddIsRecommendedAttribute implements DataPatchInterface
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
            ProductInterface::IS_RECOMMENDED,
            [
                'group' => 'Product Details',
                'type' => 'int',
                'label' => 'Recommended',
                'input' => 'boolean',
                'backend' => Boolean::class,
                'required' => false,
                'sort_order' => 17,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'user_defined' => true,
                'visible' => true,
                'used_in_product_listing' => true,
            ]
        );

        $productSetup->updateAttribute(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            ProductInterface::IS_RECOMMENDED,
            [
                'used_for_sort_by' => 1,
            ]
        );

        return $this;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Setup\Patch\Data;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Nextouch\Catalog\Api\Data\ProductInterface;
use Nextouch\Catalog\Model\Config\Product\SelectableCouriers;

class AddSelectableCouriersAttribute implements DataPatchInterface
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
            ProductInterface::SELECTABLE_COURIERS,
            [
                'group' => 'Product Details',
                'type' => 'text',
                'label' => 'Selectable Couriers',
                'input' => 'multiselect',
                'source' => SelectableCouriers::class,
                'backend' => ArrayBackend::class,
                'required' => false,
                'sort_order' => 25,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'user_defined' => true,
                'visible' => true,
            ]
        );

        return $this;
    }
}

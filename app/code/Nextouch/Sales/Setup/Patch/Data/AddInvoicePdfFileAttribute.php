<?php
declare(strict_types=1);

namespace Nextouch\Sales\Setup\Patch\Data;

use Amasty\Orderattr\Model\ResourceModel\Entity\Entity;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Nextouch\Sales\Api\Data\OrderInterface;

class AddInvoicePdfFileAttribute implements DataPatchInterface
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

    public function apply(): self
    {
        $orderSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $orderSetup->addAttribute(
            Entity::ENTITY_TYPE_CODE,
            OrderInterface::INVOICE_PDF_FILE,
            [
                'type' => 'varchar',
                'label' => 'Invoice File',
                'input' => 'file',
                'required' => false,
                'sort_order' => 10,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'user_defined' => true,
            ]
        );

        $orderSetup->updateAttribute(
            Entity::ENTITY_TYPE_CODE,
            OrderInterface::INVOICE_PDF_FILE,
            [
                'is_visible_on_back' => true,
                'validate_rules' => '{"max_file_size":5,"max_text_length":255}',
                'checkout_step' => 999,
            ]
        );

        return $this;
    }
}

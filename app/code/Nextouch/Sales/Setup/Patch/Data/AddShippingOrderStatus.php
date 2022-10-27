<?php
declare(strict_types=1);

namespace Nextouch\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Nextouch\Sales\Model\Order\Status;

class AddShippingOrderStatus implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
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
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->addOrderStatus();
        $this->addOrderState();

        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }

    public function addOrderStatus(): void
    {
        $status = [
            'status' => Status::SHIPPING['status'],
            'label' => __('Shipping'),
        ];

        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('sales_order_status'),
            $status
        );
    }

    public function addOrderState(): void
    {
        $state = [
            'status' => Status::SHIPPING['status'],
            'state' => Status::SHIPPING['state'],
            'visible_on_front' => true,
        ];

        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('sales_order_status_state'),
            $state
        );
    }
}

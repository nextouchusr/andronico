<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order;

use Nextouch\Sales\Api\Data\OrderItemInterface;
use function Lambdish\Phunctional\search;

class Item extends \Magento\Sales\Model\Order\Item implements OrderItemInterface
{
    public function getSelectedOptions(): array
    {
        return $this->getProductOptionByCode(self::SELECTED_OPTIONS);
    }

    public function hasProductInstallService(): bool
    {
        return $this->hasSelectedService(self::PRODUCT_INSTALL_SERVICE);
    }

    public function hasUsedPickService(): bool
    {
        return $this->hasSelectedService(self::USED_PICK_SERVICE);
    }

    public function hasAssemblyService(): bool
    {
        return $this->hasSelectedService(self::ASSEMBLY_SERVICE);
    }

    public function hasDisassemblyService(): bool
    {
        return $this->hasSelectedService(self::DISASSEMBLY_SERVICE);
    }

    public function hasAtFloorService(): bool
    {
        return $this->hasSelectedService(self::AT_FLOOR_SERVICE);
    }

    public function hasUnpackService(): bool
    {
        return $this->hasSelectedService(self::UNPACK_SERVICE);
    }

    public function hasDeliveryByAppointmentService(): bool
    {
        return $this->hasSelectedService(self::DELIVERY_BY_APPOINTMENT_SERVICE);
    }

    public function hasProductPickupService(): bool
    {
        return $this->hasSelectedService(self::PRODUCT_PICKUP_SERVICE);
    }

    public function hasProductDeliveryService(): bool
    {
        return $this->hasSelectedService(self::PRODUCT_DELIVERY_SERVICE);
    }

    public function hasNoticeCallService(): bool
    {
        return $this->hasSelectedService(self::NOTICE_CALL_SERVICE);
    }

    private function hasSelectedService(string $code): bool
    {
        $options = $this->getSelectedOptions();

        $service = search(fn(array $item) => $item['value'] === $code, $options);

        return $service !== null;
    }
}

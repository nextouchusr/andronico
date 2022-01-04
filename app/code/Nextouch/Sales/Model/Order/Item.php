<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Catalog\Api\Data\ProductInterface;
use Nextouch\Catalog\Api\ProductRepositoryInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use function Lambdish\Phunctional\search;

class Item extends \Magento\Sales\Model\Order\Item implements OrderItemInterface
{
    public function getProduct(): ?ProductInterface
    {
        if ($this->getData(self::PRODUCT)) {
            return $this->getData(self::PRODUCT);
        }

        try {
            $productRepository = ObjectManager::getInstance()->get(ProductRepositoryInterface::class);
            $product = $productRepository->getById((int) $this->getProductId());
            $this->setData(self::PRODUCT, $product);

            return $product;
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    public function getSelectedOptions(): array
    {
        return (array) $this->getProductOptionByCode(self::SELECTED_OPTIONS);
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

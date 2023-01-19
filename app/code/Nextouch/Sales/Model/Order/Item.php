<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Catalog\Api\Data\ProductInterface;
use Nextouch\Catalog\Api\ProductRepositoryInterface;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\some;

class Item extends \Magento\Sales\Model\Order\Item implements OrderItemInterface
{
    public function getBarcode(): string
    {
        $incrementId = $this->getOrder()->getIncrementId();

        return 'NT' . $incrementId;
    }

    public function getOrder(): ?OrderInterface
    {
        if ($this->getData(self::ORDER)) {
            return $this->getData(self::ORDER);
        }

        try {
            $orderRepository = ObjectManager::getInstance()->get(OrderRepositoryInterface::class);
            $order = $orderRepository->get((int) $this->getOrderId());
            $this->setData(self::ORDER, $order);

            return $order;
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

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

    public function getSelectedOptionLabels(): string
    {
        $labels = reduce(function (array $acc, array $option) {
            return [$option['value'], ...$acc];
        }, $this->getSelectedOptions(), []);

        return implode(',', $labels);
    }

    public function hasStreetLineDelivery(): bool
    {
        return $this->getProduct()->getStreetLineDeliveryPrice() > 0;
    }

    public function hasFloorDelivery(): bool
    {
        return $this->getProduct()->getFloorDeliveryPrice() > 0;
    }

    public function hasEveningDelivery(): bool
    {
        return $this->hasSelectedService(ProductInterface::EVENING_DELIVERY);
    }

    public function hasAppointmentDelivery(): bool
    {
        return $this->hasSelectedService(ProductInterface::APPOINTMENT_DELIVERY);
    }

    public function hasSaturdayDelivery(): bool
    {
        return $this->hasSelectedService(ProductInterface::SATURDAY_DELIVERY);
    }

    public function hasProductInstall(): bool
    {
        return $this->hasSelectedService(ProductInterface::PRODUCT_INSTALLATION);
    }

    public function hasBuiltInProduct(): bool
    {
        return $this->hasSelectedService(ProductInterface::BUILT_IN_PRODUCT);
    }

    public function hasGasCertification(): bool
    {
        return $this->hasSelectedService(ProductInterface::GAS_CERTIFICATION);
    }

    public function hasStandardTvInstallation(): bool
    {
        return $this->hasSelectedService(ProductInterface::STANDARD_TV_INSTALLATION);
    }

    public function hasConnectivityAndTvDemonstration(): bool
    {
        return $this->hasSelectedService(ProductInterface::CONNECTIVITY_AND_TV_DEMONSTRATION);
    }

    public function hasPeripheralInstallationToTv(): bool
    {
        return $this->hasSelectedService(ProductInterface::PERIPHERAL_INSTALLATION_TO_TV);
    }

    public function hasTvWallMounting(): bool
    {
        return $this->hasSelectedService(ProductInterface::TV_WALL_MOUNTING);
    }

    public function hasRaeeReturn(): bool
    {
        return $this->hasSelectedService(ProductInterface::RAEE_RETURN);
    }

    public function hasAirConditioningInstallationMono(): bool
    {
        return $this->hasSelectedService(ProductInterface::AIR_CONDITIONING_INSTALLATION_MONO);
    }

    public function hasAirConditioningInstallationDual(): bool
    {
        return $this->hasSelectedService(ProductInterface::AIR_CONDITIONING_INSTALLATION_DUAL);
    }

    public function hasAirConditioningInstallationTrial(): bool
    {
        return $this->hasSelectedService(ProductInterface::AIR_CONDITIONING_INSTALLATION_TRIAL);
    }

    private function hasSelectedService(string $code): bool
    {
        $options = $this->getSelectedOptions();

        return some(function (array $option) use ($code) {
            $services = explode(self::OPTION_SEPARATOR, $option['option_sku']);

            return in_array($code, $services);
        }, $options);
    }
}

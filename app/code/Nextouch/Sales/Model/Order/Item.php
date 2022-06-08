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

    public function hasProductInstall(): bool
    {
        return (
            $this->hasStandardTvInstallation() ||
            $this->hasPeripheralInstallationToTv() ||
            $this->hasAirConditioningInstallationMono() ||
            $this->hasAirConditioningInstallationDual() ||
            $this->hasAirConditioningInstallationTrial()
        );
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
        return $this->hasSelectedService(self::EVENING_DELIVERY);
    }

    public function hasAppointmentDelivery(): bool
    {
        return $this->hasSelectedService(self::APPOINTMENT_DELIVERY);
    }

    public function hasUrgentDelivery(): bool
    {
        return $this->hasSelectedService(self::URGENT_DELIVERY);
    }

    public function hasSaturdayDelivery(): bool
    {
        return $this->hasSelectedService(self::SATURDAY_DELIVERY);
    }

    public function hasHookupToElectricalGrid(): bool
    {
        return $this->hasSelectedService(self::HOOKUP_TO_ELECTRICAL_GRID);
    }

    public function hasHookupAndBuildIn(): bool
    {
        return $this->hasSelectedService(self::HOOKUP_AND_BUILD_IN);
    }

    public function hasStandardTvInstallation(): bool
    {
        return $this->hasSelectedService(self::STANDARD_TV_INSTALLATION);
    }

    public function hasConnectivityAndTvDemonstration(): bool
    {
        return $this->hasSelectedService(self::CONNECTIVITY_AND_TV_DEMONSTRATION);
    }

    public function hasPeripheralInstallationToTv(): bool
    {
        return $this->hasSelectedService(self::PERIPHERAL_INSTALLATION_TO_TV);
    }

    public function hasTvWallMounting(): bool
    {
        return $this->hasSelectedService(self::TV_WALL_MOUNTING);
    }

    public function hasAirConditioningInstallationMono(): bool
    {
        return $this->hasSelectedService(self::AIR_CONDITIONING_INSTALLATION_MONO);
    }

    public function hasAirConditioningInstallationDual(): bool
    {
        return $this->hasSelectedService(self::AIR_CONDITIONING_INSTALLATION_DUAL);
    }

    public function hasAirConditioningInstallationTrial(): bool
    {
        return $this->hasSelectedService(self::AIR_CONDITIONING_INSTALLATION_TRIAL);
    }

    public function hasSmartphoneFileTransfer(): bool
    {
        return $this->hasSelectedService(self::SMARTPHONE_FILE_TRANSFER);
    }

    public function hasSmartphoneReadyForYou(): bool
    {
        return $this->hasSelectedService(self::SMARTPHONE_READY_FOR_YOU);
    }

    public function hasPcReadyForYou(): bool
    {
        return $this->hasSelectedService(self::PC_READY_FOR_YOU);
    }

    public function hasPcReadyForYouPremium(): bool
    {
        return $this->hasSelectedService(self::PC_READY_FOR_YOU_PREMIUM);
    }

    private function hasSelectedService(string $code): bool
    {
        $options = $this->getSelectedOptions();

        $service = search(fn(array $item) => $item['value'] === $code, $options);

        return $service !== null;
    }
}

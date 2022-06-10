<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

use Nextouch\Catalog\Api\Data\ProductInterface;

/**
 * @api
 */
interface OrderItemInterface extends \Magento\Sales\Api\Data\OrderItemInterface
{
    public const PRODUCT = 'product';
    public const SELECTED_OPTIONS = 'options';

    // Product Service Options
    public const EVENING_DELIVERY = 'evening_delivery';
    public const APPOINTMENT_DELIVERY = 'appointment_delivery';
    public const URGENT_DELIVERY = 'urgent_delivery';
    public const SATURDAY_DELIVERY = 'saturday_delivery';
    public const HOOKUP_TO_ELECTRICAL_GRID = 'hookup_to_electrical_grid';
    public const HOOKUP_AND_BUILD_IN = 'hookup_and_build_in';
    public const STANDARD_TV_INSTALLATION = 'standard_tv_installation';
    public const CONNECTIVITY_AND_TV_DEMONSTRATION = 'connectivity_and_tv_demonstration';
    public const PERIPHERAL_INSTALLATION_TO_TV = 'peripheral_installation_to_tv';
    public const TV_WALL_MOUNTING = 'tv_wall_mounting';
    public const AIR_CONDITIONING_INSTALLATION_MONO = 'air_conditioning_installation_mono';
    public const AIR_CONDITIONING_INSTALLATION_DUAL = 'air_conditioning_installation_dual';
    public const AIR_CONDITIONING_INSTALLATION_TRIAL = 'air_conditioning_installation_trial';
    public const SMARTPHONE_FILE_TRANSFER = 'smartphone_file_transfer';
    public const SMARTPHONE_READY_FOR_YOU = 'smartphone_ready_for_you';
    public const PC_READY_FOR_YOU = 'pc_ready_for_you';
    public const PC_READY_FOR_YOU_PREMIUM = 'pc_ready_for_you_premium';

    /**
     * @return \Nextouch\Catalog\Api\Data\ProductInterface|null
     */
    public function getProduct(): ?ProductInterface;

    /**
     * @return array
     */
    public function getSelectedOptions(): array;

    /**
     * @return bool
     */
    public function hasProductInstall(): bool;

    /**
     * @return bool
     */
    public function hasStreetLineDelivery(): bool;

    /**
     * @return bool
     */
    public function hasFloorDelivery(): bool;

    /**
     * @return bool
     */
    public function hasEveningDelivery(): bool;

    /**
     * @return bool
     */
    public function hasAppointmentDelivery(): bool;

    /**
     * @return bool
     */
    public function hasUrgentDelivery(): bool;

    /**
     * @return bool
     */
    public function hasSaturdayDelivery(): bool;

    /**
     * @return bool
     */
    public function hasHookupToElectricalGrid(): bool;

    /**
     * @return bool
     */
    public function hasHookupAndBuildIn(): bool;

    /**
     * @return bool
     */
    public function hasStandardTvInstallation(): bool;

    /**
     * @return bool
     */
    public function hasConnectivityAndTvDemonstration(): bool;

    /**
     * @return bool
     */
    public function hasPeripheralInstallationToTv(): bool;

    /**
     * @return bool
     */
    public function hasTvWallMounting(): bool;

    /**
     * @return bool
     */
    public function hasAirConditioningInstallationMono(): bool;

    /**
     * @return bool
     */
    public function hasAirConditioningInstallationDual(): bool;

    /**
     * @return bool
     */
    public function hasAirConditioningInstallationTrial(): bool;

    /**
     * @return bool
     */
    public function hasSmartphoneFileTransfer(): bool;

    /**
     * @return bool
     */
    public function hasSmartphoneReadyForYou(): bool;

    /**
     * @return bool
     */
    public function hasPcReadyForYou(): bool;

    /**
     * @return bool
     */
    public function hasPcReadyForYouPremium(): bool;
}

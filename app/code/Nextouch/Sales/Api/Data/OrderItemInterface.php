<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

use Nextouch\Catalog\Api\Data\ProductInterface;

/**
 * @api
 */
interface OrderItemInterface extends \Magento\Sales\Api\Data\OrderItemInterface
{
    public const ORDER = 'order';
    public const PRODUCT = 'product';
    public const SELECTED_OPTIONS = 'options';
    public const OPTION_SEPARATOR = ',';

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface|null
     */
    public function getOrder(): ?OrderInterface;

    /**
     * @return \Nextouch\Catalog\Api\Data\ProductInterface|null
     */
    public function getProduct(): ?ProductInterface;

    /**
     * @return array
     */
    public function getSelectedOptions(): array;

    /**
     * @return string
     */
    public function getSelectedOptionLabels(): string;

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
    public function hasSaturdayDelivery(): bool;

    /**
     * @return bool
     */
    public function hasProductInstall(): bool;

    /**
     * @return bool
     */
    public function hasBuiltInProduct(): bool;

    /**
     * @return bool
     */
    public function hasGasCertification(): bool;

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
    public function hasRaeeReturn(): bool;

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
}

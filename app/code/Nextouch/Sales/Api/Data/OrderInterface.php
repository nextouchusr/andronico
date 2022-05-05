<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

use Amasty\Deliverydate\Api\Data\DeliverydateInterface;

/**
 * @api
 */
interface OrderInterface extends \Magento\Sales\Api\Data\OrderInterface
{
    public const DELIVERY_INFORMATION = 'delivery_information';
    public const ORDER_SYNC_FAILURES = 'order_sync_failures';
    public const INVOICE_PDF_FILE = 'invoice_pdf_file';
    public const FINDOMESTIC_APPLICATION_ID = 'findomestic_application_id';
    public const FINDOMESTIC_ISSUER_INSTALLMENT_ID = 'findomestic_issuer_installment_id';
    public const FINDOMESTIC_APPLICATION_STATUS = 'findomestic_application_status';

    public const FINDOMESTIC_APPLICATION_STATUS_INACTIVE = 0;
    public const FINDOMESTIC_APPLICATION_STATUS_TO_ACTIVATE = 1;
    public const FINDOMESTIC_APPLICATION_STATUS_ACTIVE = 2;

    /**
     * @param string $shippingMethod
     * @return bool
     */
    public function isShippedBy(string $shippingMethod): bool;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderAddressInterface|null
     */
    public function getShippingAddress(): ?OrderAddressInterface;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderAddressInterface|null
     */
    public function getBillingAddress(): ?OrderAddressInterface;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderItemInterface[]
     */
    public function getItems(): array;

    /**
     * @param $itemId
     * @return \Nextouch\Sales\Api\Data\OrderItemInterface|null
     */
    public function getItemById($itemId): ?OrderItemInterface;

    /**
     * @return \Amasty\Deliverydate\Api\Data\DeliverydateInterface
     */
    public function getDeliveryInformation(): DeliverydateInterface;

    /**
     * @return string
     */
    public function getDeliveryDate(): string;

    /**
     * @return string
     */
    public function getDeliveryTime(): string;

    /**
     * @return string
     */
    public function getDeliveryComment(): string;

    /**
     * @return int
     */
    public function getOrderSyncFailures(): int;

    /**
     * @return OrderInterface
     */
    public function increaseOrderSyncFailures(): self;

    /**
     * @return OrderInterface
     */
    public function decreaseOrderSyncFailures(): self;

    /**
     * @return OrderInterface
     */
    public function resetOrderSyncFailures(): self;

    /**
     * @return string
     */
    public function getFindomesticApplicationId(): string;

    /**
     * @param string $applicationId
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function setFindomesticApplicationId(string $applicationId): self;

    /**
     * @return string
     */
    public function getFindomesticIssuerInstallmentId(): string;

    /**
     * @param string $issuerInstallmentId
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function setFindomesticIssuerInstallmentId(string $issuerInstallmentId): self;

    /**
     * @return bool
     */
    public function isFindomesticApplicationInactive(): bool;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function setFindomesticApplicationInactive(): self;

    /**
     * @return bool
     */
    public function isFindomesticApplicationToActivate(): bool;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function setFindomesticApplicationToActivate(): self;

    /**
     * @return bool
     */
    public function isFindomesticApplicationActive(): bool;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function setFindomesticApplicationActive(): self;

    /**
     * @return bool
     */
    public function canActivateInstallment(): bool;
}

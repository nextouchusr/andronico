<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api\Data;

use Amasty\Deliverydate\Api\Data\DeliverydateInterface;

/**
 * @api
 */
interface OrderInterface extends \Magento\Sales\Api\Data\OrderInterface
{
    public const COMPLETED_AT = 'completed_at';
    public const DELIVERY_INFORMATION = 'delivery_information';
    public const ORDER_SYNC_FAILURES = 'order_sync_failures';
    public const SHIPPING_SYNC_FAILURES = 'shipping_sync_failures';
    public const IS_PARKED = 'is_parked';
    public const INVOICE_PDF_FILE = 'invoice_pdf_file';
    public const FINDOMESTIC_APPLICATION_ID = 'findomestic_application_id';
    public const FINDOMESTIC_ISSUER_INSTALLMENT_ID = 'findomestic_issuer_installment_id';
    public const FINDOMESTIC_APPLICATION_STATUS = 'findomestic_application_status';

    public const FINDOMESTIC_APPLICATION_STATUS_INACTIVE = 0;
    public const FINDOMESTIC_APPLICATION_STATUS_TO_ACTIVATE = 1;
    public const FINDOMESTIC_APPLICATION_STATUS_ACTIVE = 2;
    public const FINDOMESTIC_APPLICATION_STATUS_CANCEL = 3;
    public const FINDOMESTIC_APPLICATION_STATUS_DECLINE = 4;

    public const IN_STORE_ORDER_ACCEPTED = 'A';
    public const IN_STORE_ORDER_REJECTED = 'R';
    public const IN_STORE_ORDER_PICKED_UP = 'S';
    public const IN_STORE_ORDER_NOT_PICKED_UP = 'N';

    /**
     * @return bool
     */
    public function isShippedByFastEst(): bool;

    /**
     * @return bool
     */
    public function isShippedByGls(): bool;

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
     * @return string
     */
    public function getCompletedAt(): ?string;

    /**
     * @param string $completedAt
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function setCompletedAt(string $completedAt): self;

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
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function increaseOrderSyncFailures(): self;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function decreaseOrderSyncFailures(): self;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function resetOrderSyncFailures(): self;

    /**
     * @return int
     */
    public function getShippingSyncFailures(): int;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function increaseShippingSyncFailures(): self;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function decreaseShippingSyncFailures(): self;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function resetShippingSyncFailures(): self;

    /**
     * @return bool
     */
    public function getIsParked(): bool;

    /**
     * @param bool $isParked
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function setIsParked(bool $isParked): self;

    /**
     * @return bool
     */
    public function isPaid(): bool;

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
    public function isFindomesticApplicationCancel(): bool;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function setFindomesticApplicationCancel(): self;

    /**
     * @return bool
     */
    public function isFindomesticApplicationDecline(): bool;

    /**
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     */
    public function setFindomesticApplicationDecline(): self;

    /**
     * @return bool
     */
    public function hasFindomesticPayment(): bool;

    /**
     * @return bool
     */
    public function canActivateInstallment(): bool;
}

<?php
declare(strict_types=1);

namespace Nextouch\Quote\Api\Data;

/**
 * @api
 */
interface CartInterface extends \Magento\Quote\Api\Data\CartInterface
{
    public const FINDOMESTIC_APPLICATION_ID = 'findomestic_application_id';
    public const FINDOMESTIC_ISSUER_INSTALLMENT_ID = 'findomestic_issuer_installment_id';

    /**
     * @return \Nextouch\Quote\Api\Data\AddressInterface|null
     */
    public function getShippingAddress(): ?AddressInterface;

    /**
     * @return \Nextouch\Quote\Api\Data\AddressInterface|null
     */
    public function getBillingAddress(): ?AddressInterface;

    /**
     * @return string
     */
    public function getFindomesticApplicationId(): string;

    /**
     * @param string $applicationId
     * @return \Nextouch\Quote\Api\Data\CartInterface
     */
    public function setFindomesticApplicationId(string $applicationId): self;

    /**
     * @return string
     */
    public function getFindomesticIssuerInstallmentId(): string;

    /**
     * @param string $issuerInstallmentId
     * @return \Nextouch\Quote\Api\Data\CartInterface
     */
    public function setFindomesticIssuerInstallmentId(string $issuerInstallmentId): self;

    /**
     * @return \Nextouch\Quote\Api\Data\CartItemInterface[]
     */
    public function getItems(): array;

    /**
     * @return bool
     */
    public function isShippableWithInStorePickup(): bool;

    /**
     * @return bool
     */
    public function isShippableWithFastEst(): bool;

    /**
     * @return bool
     */
    public function isShippableWithDhl(): bool;

    /**
     * @return bool
     */
    public function isShippableWithGls(): bool;
}

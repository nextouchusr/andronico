<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Model;

use Axepta\Paymentservice\Model\Axepta;
use Magento\Checkout\Api\Data\PaymentDetailsInterface;
use Magento\Checkout\Api\PaymentInformationManagementInterface;
use Magento\Checkout\Api\ShippingInformationManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\InventoryInStorePickupShippingApi\Model\Carrier\InStorePickup;
use Magento\OfflinePayments\Model\Banktransfer;
use Magento\Payment\Model\MethodInterface as PaymentMethodInterface;
use Magento\Paypal\Model\Config;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Nextouch\Findomestic\Model\Findomestic;
use Nextouch\InStorePayment\Model\InStorePayment;
use function Lambdish\Phunctional\filter;

class FilterPaymentMethodsBasedOnCart
{
    private CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetPaymentInformation(
        PaymentInformationManagementInterface $subject,
        PaymentDetailsInterface $result,
        string $cartId
    ): PaymentDetailsInterface {
        $paymentMethods = $this->filterPaymentMethods($result, $cartId);
        $result->setPaymentMethods($paymentMethods);

        return $result;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterSaveAddressInformation(
        ShippingInformationManagementInterface $subject,
        PaymentDetailsInterface $result,
        string $cartId
    ): PaymentDetailsInterface {
        $paymentMethods = $this->filterPaymentMethods($result, $cartId);
        $result->setPaymentMethods($paymentMethods);

        return $result;
    }

    /**
     * @throws LocalizedException
     */
    private function filterPaymentMethods(PaymentDetailsInterface $paymentDetails, string $cartId): array
    {
        /** @var Quote $quote */
        $quote = $this->cartRepository->get($cartId);
        $shippingMethod = $quote->getShippingAddress()->getShippingMethod();

        return filter(function (PaymentMethodInterface $item) use ($shippingMethod) {
            $showForCarrierDelivery = $this->showForCarrierDelivery($item, $shippingMethod);
            $showForInStoreDelivery = $this->showForInStoreDelivery($item, $shippingMethod);

            return $showForCarrierDelivery || $showForInStoreDelivery;
        }, $paymentDetails->getPaymentMethods());
    }

    private function showForCarrierDelivery(PaymentMethodInterface $paymentMethod, string $shippingMethod): bool
    {
        $isCarrierMethod = $shippingMethod !== InStorePickup::DELIVERY_METHOD;
        $canUseAsCarrierPayment = in_array($paymentMethod->getCode(), [
            Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE,
            Config::METHOD_EXPRESS,
            Axepta::METHOD_CODE,
            Findomestic::METHOD_CODE,
        ]);

        return $isCarrierMethod && $canUseAsCarrierPayment;
    }

    private function showForInStoreDelivery(PaymentMethodInterface $paymentMethod, string $shippingMethod): bool
    {
        $isInStorePickupMethod = $shippingMethod === InStorePickup::DELIVERY_METHOD;
        $canUseAsInStorePayment = in_array($paymentMethod->getCode(), [
            InStorePayment::METHOD_CODE,
            Config::METHOD_EXPRESS,
            Axepta::METHOD_CODE,
        ]);

        return $isInStorePickupMethod && $canUseAsInStorePayment;
    }
}

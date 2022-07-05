<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Plugin\Helper\Deliverydate;

use Amasty\Deliverydate\Helper\Data;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Catalog\Api\Data\ProductInterface;
use Nextouch\Quote\Api\Data\CartInterface;
use Nextouch\Quote\Api\Data\CartItemInterface;
use Nextouch\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use function Lambdish\Phunctional\some;

class RenderDeliveryByAppointmentFields
{
    private CheckoutSession $checkoutSession;
    private QuoteCollectionFactory $quoteCollectionFactory;

    public function __construct(
        CheckoutSession $checkoutSession,
        QuoteCollectionFactory $quoteCollectionFactory
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterModuleEnabled(Data $subject, bool $result): bool
    {
        return $result && $this->hasAppointmentDeliveryService();
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterIsFieldEnabled(Data $subject, bool $result): bool
    {
        return $result && $this->hasAppointmentDeliveryService();
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterIsDeliveryTimeEnabled(Data $subject, bool $result): bool
    {
        return $result && $this->hasAppointmentDeliveryService();
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterIsDeliveryCommentEnabled(Data $subject, bool $result): bool
    {
        return $result && $this->hasAppointmentDeliveryService();
    }

    /**
     * @throws LocalizedException
     */
    private function hasAppointmentDeliveryService(): bool
    {
        $quote = $this->getQuote();
        $quoteItems = $quote ? $quote->getItems() : [];

        return some(function (CartItemInterface $item) {
            return $item->hasSelectedService(ProductInterface::APPOINTMENT_DELIVERY);
        }, $quoteItems);
    }

    /**
     * @throws LocalizedException
     */
    private function getQuote(): ?CartInterface
    {
        $quote = $this->checkoutSession->getQuote();

        /** @var CartInterface|null $result */
        $result = $this->quoteCollectionFactory->create()->getItemById((int) $quote->getId());

        return $result;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Plugin\Axepta\Controller\Payment;

use Collections\Exceptions\InvalidArgumentException;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\FastEst\Service\CreateNewDelivery as CreateNewDeliveryService;

class CreateNewDelivery
{
    private CheckoutSession $checkoutSession;
    private CreateNewDeliveryService $createNewDeliveryService;

    public function __construct(
        CheckoutSession $checkoutSession,
        CreateNewDeliveryService $createNewDeliveryService
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->createNewDeliveryService = $createNewDeliveryService;
    }

    /**
     * @throws LocalizedException
     * @throws InvalidArgumentException
     */
    public function afterExecute(): void
    {
        /** @var OrderInterface $order */
        $order = $this->checkoutSession->getLastRealOrder();

        $this->createNewDeliveryService->create($order);
    }
}

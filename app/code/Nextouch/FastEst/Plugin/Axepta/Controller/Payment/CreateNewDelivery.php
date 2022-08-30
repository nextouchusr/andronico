<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Plugin\Axepta\Controller\Payment;

use Collections\Exceptions\InvalidArgumentException;
use Magento\Checkout\Model\Type\Onepage as OnepageCheckout;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\FastEst\Service\CreateNewDelivery as CreateNewDeliveryService;

class CreateNewDelivery
{
    private OnepageCheckout $onepageCheckout;
    private CreateNewDeliveryService $createNewDeliveryService;

    public function __construct(
        OnepageCheckout $onepageCheckout,
        CreateNewDeliveryService $createNewDeliveryService
    ) {
        $this->onepageCheckout = $onepageCheckout;
        $this->createNewDeliveryService = $createNewDeliveryService;
    }

    /**
     * @throws LocalizedException
     * @throws InvalidArgumentException
     */
    public function afterExecute(): void
    {
        $order = $this->getLastRealOrder();

        $this->createNewDeliveryService->create($order);
    }

    private function getLastRealOrder(): OrderInterface
    {
        return $this->onepageCheckout->getCheckout()->getLastRealOrder();
    }
}

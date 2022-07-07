<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Observer;

use Collections\Exceptions\InvalidArgumentException;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\FastEst\Service\CreateNewDelivery as CreateNewDeliveryService;

class CreateNewDelivery implements ObserverInterface
{
    private CreateNewDeliveryService $createNewDeliveryService;

    public function __construct(CreateNewDeliveryService $createNewDeliveryService)
    {
        $this->createNewDeliveryService = $createNewDeliveryService;
    }

    /**
     * @throws LocalizedException
     * @throws InvalidArgumentException
     */
    public function execute(Observer $observer): void
    {
        /** @var OrderInterface $order */
        $order = $observer->getData('order');

        $this->createNewDeliveryService->create($order);
    }
}

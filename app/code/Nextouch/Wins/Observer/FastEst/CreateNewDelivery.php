<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer\FastEst;

use Collections\Exceptions\InvalidArgumentException;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Nextouch\FastEst\Service\CreateNewDelivery as CreateNewDeliveryService;

class CreateNewDelivery implements ObserverInterface
{
    private OrderRepositoryInterface $orderRepository;
    private CreateNewDeliveryService $createNewDeliveryService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CreateNewDeliveryService $createNewDeliveryService
    ) {
        $this->orderRepository = $orderRepository;
        $this->createNewDeliveryService = $createNewDeliveryService;
    }

    /**
     * @throws LocalizedException
     * @throws InvalidArgumentException
     */
    public function execute(Observer $observer): void
    {
        $orderId = (int) $observer->getData('orderId');
        $order = $this->orderRepository->get($orderId);

        $this->createNewDeliveryService->create($order);
    }
}

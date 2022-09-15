<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer\Axepta;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Nextouch\Wins\Service\Order\CreateNewOrder as CreateNewOrderService;

class CreateNewOrder implements ObserverInterface
{
    private CreateNewOrderService $createNewOrderService;

    public function __construct(CreateNewOrderService $createNewOrderService)
    {
        $this->createNewOrderService = $createNewOrderService;
    }

    public function execute(Observer $observer): void
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        $this->createNewOrderService->create($order);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Sales\Plugin\Model;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface as NextouchOrderRepositoryInterface;
use Nextouch\Sales\Model\Order\Status;

class DispatchCompleteOrderEvent
{
    private NextouchOrderRepositoryInterface $orderRepository;
    private ManagerInterface $eventManager;

    public function __construct(
        NextouchOrderRepositoryInterface $orderRepository,
        ManagerInterface $eventManager
    ) {
        $this->orderRepository = $orderRepository;
        $this->eventManager = $eventManager;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function aroundSave(
        OrderRepositoryInterface $subject,
        callable $proceed,
        OrderInterface $entity
    ): OrderInterface {
        $orderId = (int) $entity->getEntityId();

        if (!$orderId) {
            return $proceed($entity);
        }

        $origin = $this->orderRepository->get($orderId);
        $wasShippedStatus = $origin->getStatus() === Status::SHIPPED['status'];
        $wasShippedState = $origin->getState() === Status::SHIPPED['state'];

        /** @var OrderInterface $result */
        $result = $proceed($entity);

        if ($wasShippedStatus && $wasShippedState) {
            $nextouchOrder = $this->orderRepository->get((int) $result->getEntityId());

            $this->eventManager->dispatch('sales_order_status_change_to_complete', [
                'order' => $nextouchOrder,
            ]);
        }

        return $result;
    }
}

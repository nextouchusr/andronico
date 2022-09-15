<?php
declare(strict_types=1);

namespace Nextouch\Sales\Plugin\Model;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\Sales\Model\Order\Status;

class ForceInDeliveryOrderState
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeSave(OrderRepositoryInterface $subject, OrderInterface $entity): array
    {
        /** @var Order $entity */
        $isInDeliveryStatus = $entity->getStatus() === Status::IN_DELIVERY['status'];

        if ($isInDeliveryStatus) {
            $entity->setState(Status::IN_DELIVERY['state']);
        }

        return [$entity];
    }
}

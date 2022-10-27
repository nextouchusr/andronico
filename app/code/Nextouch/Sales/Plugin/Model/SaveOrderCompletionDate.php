<?php
declare(strict_types=1);

namespace Nextouch\Sales\Plugin\Model;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\Sales\Model\Order\Status;

class SaveOrderCompletionDate
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeSave(OrderRepositoryInterface $subject, OrderInterface $entity): array
    {
        /** @var Order $entity */
        $isCompleteStatus = in_array($entity->getStatus(), [Status::PARTIALLY_COMPLETE['status'], Status::COMPLETE['status']]);
        $isCompleteState = in_array($entity->getState(), [Status::PARTIALLY_COMPLETE['state'], Status::COMPLETE['state']]);
        $hasCompleteDate = $entity->getData('completed_at');

        if ($isCompleteStatus && $isCompleteState && !$hasCompleteDate) {
            $entity->setData('completed_at', date('Y-m-d H:i:s'));
        }

        return [$entity];
    }
}

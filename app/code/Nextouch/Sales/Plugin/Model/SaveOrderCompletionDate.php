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
        $isCompleteStatus = $entity->getStatus() === Status::COMPLETE['status'];
        $isCompleteState = $entity->getState() === Status::COMPLETE['state'];

        if ($isCompleteStatus && $isCompleteState) {
            $entity->setData('completed_at', date('Y-m-d H:i:s'));
        }

        return [$entity];
    }
}

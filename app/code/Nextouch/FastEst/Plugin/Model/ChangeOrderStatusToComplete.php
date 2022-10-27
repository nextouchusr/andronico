<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Plugin\Model;

use Magento\Framework\Exception\LocalizedException;
use Nextouch\Sales\Api\OrderManagementInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Wins\Service\Order\SendCurrentOrderStatus;

class ChangeOrderStatusToComplete
{
    private OrderRepositoryInterface $orderRepository;
    private SendCurrentOrderStatus $sendCurrentOrderStatus;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SendCurrentOrderStatus $sendCurrentOrderStatus
    ) {
        $this->orderRepository = $orderRepository;
        $this->sendCurrentOrderStatus = $sendCurrentOrderStatus;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterComplete(OrderManagementInterface $subject, int $result): int
    {
        $order = $this->orderRepository->get($result);

        if ($order->isShippedByFastEst()) {
            $this->sendCurrentOrderStatus->execute($order);
        }

        return $result;
    }
}

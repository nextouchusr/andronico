<?php
declare(strict_types=1);

namespace Nextouch\Rma\Plugin\Helper;

use Magento\Framework\Exception\LocalizedException;
use Magento\Rma\Helper\Data;
use Nextouch\Rma\Helper\RmaConfig;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;

class CanRequestRma
{
    private OrderRepositoryInterface $orderRepository;
    private RmaConfig $rmaConfig;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        RmaConfig $rmaConfig
    ) {
        $this->orderRepository = $orderRepository;
        $this->rmaConfig = $rmaConfig;
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterCanCreateRma(Data $subject, bool $result, $maybeOrder): bool
    {
        /** @var OrderInterface $order */
        $order = is_numeric($maybeOrder) ? $this->orderRepository->get($maybeOrder) : $maybeOrder;

        $canRequestRma = false;
        if ($order->getCompletedAt()) {
            $completedAt = date_create($order->getCompletedAt());
            $today = date_create(date('Y-m-d H:i:s'));
            $daysElapsedSinceCompletion = date_diff($completedAt, $today)->days;
            $canRequestRma = $daysElapsedSinceCompletion <= $this->rmaConfig->getPolicyReturnPeriod();
        }

        return $result && $canRequestRma;
    }
}

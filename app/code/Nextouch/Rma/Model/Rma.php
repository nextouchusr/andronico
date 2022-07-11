<?php
declare(strict_types=1);

namespace Nextouch\Rma\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Rma\Model\Item;
use Magento\Rma\Model\Rma\Source\Status;
use Magento\Sales\Model\Order\ItemFactory;
use Nextouch\Rma\Api\Data\RmaInterface;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use function Lambdish\Phunctional\reduce;

class Rma extends \Magento\Rma\Model\Rma implements RmaInterface
{
    public function getOrder(): OrderInterface
    {
        $order = parent::getOrder();
        $orderId = $order ? $order->getId() : null;

        $collectionFactory = ObjectManager::getInstance()->get(OrderCollectionFactory::class);

        return $collectionFactory->create()->getItemById((int) $orderId);
    }

    public function getAmountApproved(): float
    {
        return reduce(function (float $acc, Item $item) {
            if ($item->getStatus() !== Status::STATE_APPROVED) {
                return $acc;
            }

            $orderItemFactory = ObjectManager::getInstance()->get(ItemFactory::class);
            $orderItem = $orderItemFactory->create()->load($item->getOrderItemId());
            $amountApproved = $orderItem->getPriceInclTax() * $item->getQtyApproved();

            return $acc + $amountApproved;
        }, $this->getItems(), 0);
    }

    public function getReturnSyncFailures(): int
    {
        return (int) $this->getData(self::RETURN_SYNC_FAILURES);
    }

    public function increaseReturnSyncFailures(): self
    {
        $failures = $this->getReturnSyncFailures();
        $this->setData(self::RETURN_SYNC_FAILURES, ++$failures);

        return $this;
    }

    public function decreaseReturnSyncFailures(): self
    {
        $failures = $this->getReturnSyncFailures();
        $this->setData(self::RETURN_SYNC_FAILURES, --$failures);

        return $this;
    }

    public function resetReturnSyncFailures(): self
    {
        $this->setData(self::RETURN_SYNC_FAILURES, 0);

        return $this;
    }

    public function isFindomesticRefundStatusUnsent(): bool
    {
        $status = (int) $this->getData(self::FINDOMESTIC_REFUND_STATUS);

        return $status === self::FINDOMESTIC_REFUND_STATUS_UNSENT;
    }

    public function setFindomesticRefundStatusUnsent(): self
    {
        $this->setData(self::FINDOMESTIC_REFUND_STATUS, self::FINDOMESTIC_REFUND_STATUS_UNSENT);

        return $this;
    }

    public function isFindomesticRefundStatusPending(): bool
    {
        $status = (int) $this->getData(self::FINDOMESTIC_REFUND_STATUS);

        return $status === self::FINDOMESTIC_REFUND_STATUS_PENDING;
    }

    public function setFindomesticRefundStatusPending(): self
    {
        $this->setData(self::FINDOMESTIC_REFUND_STATUS, self::FINDOMESTIC_REFUND_STATUS_PENDING);

        return $this;
    }

    public function isFindomesticRefundStatusApproved(): bool
    {
        $status = (int) $this->getData(self::FINDOMESTIC_REFUND_STATUS);

        return $status === self::FINDOMESTIC_REFUND_STATUS_APPROVED;
    }

    public function setFindomesticRefundStatusApproved(): self
    {
        $this->setData(self::FINDOMESTIC_REFUND_STATUS, self::FINDOMESTIC_REFUND_STATUS_APPROVED);

        return $this;
    }

    public function isFindomesticRefundStatusDeclined(): bool
    {
        $status = (int) $this->getData(self::FINDOMESTIC_REFUND_STATUS);

        return $status === self::FINDOMESTIC_REFUND_STATUS_DECLINED;
    }

    public function setFindomesticRefundStatusDeclined(): self
    {
        $this->setData(self::FINDOMESTIC_REFUND_STATUS, self::FINDOMESTIC_REFUND_STATUS_DECLINED);

        return $this;
    }

    public function canRefundInstallment(): bool
    {
        return (
            $this->getOrder()->hasFindomesticPayment() &&
            $this->isFindomesticRefundStatusUnsent()
        );
    }
}

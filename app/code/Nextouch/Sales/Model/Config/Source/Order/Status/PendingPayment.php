<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Config\Source\Order\Status;

use Magento\Sales\Model\Config\Source\Order\Status;
use Magento\Sales\Model\Order;

class PendingPayment extends Status
{
    protected $_stateStatuses = Order::STATE_PENDING_PAYMENT;
}

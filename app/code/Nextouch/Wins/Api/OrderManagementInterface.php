<?php
declare(strict_types=1);

namespace Nextouch\Wins\Api;

use Nextouch\Wins\Model\Request\Order\UpdateOrderState;
use Nextouch\Wins\Model\Request\Order\UpdateOrderStatus;

/**
 * @api
 */
interface OrderManagementInterface
{
    public function updateStatus(UpdateOrderStatus $request): bool;

    public function updateState(UpdateOrderState $request): bool;
}

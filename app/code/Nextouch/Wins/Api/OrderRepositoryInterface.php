<?php
declare(strict_types=1);

namespace Nextouch\Wins\Api;

use Nextouch\Wins\Model\Request\Order\CreateOrder;

/**
 * @api
 */
interface OrderRepositoryInterface
{
    public function create(CreateOrder $request): bool;
}

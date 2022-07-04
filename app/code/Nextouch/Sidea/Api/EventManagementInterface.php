<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Api;

use Nextouch\Sidea\Model\Request\Event\AbandonedCart as AbandonedCartRequest;

/**
 * @api
 */
interface EventManagementInterface
{
    public function sendAbandonedCart(AbandonedCartRequest $request): bool;
}

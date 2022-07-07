<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order\InStore;

/**
 * @api
 */
interface OrderActionInterface
{
    public function execute(string $orderId): void;
}

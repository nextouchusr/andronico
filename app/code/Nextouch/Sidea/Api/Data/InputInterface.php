<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Api\Data;

/**
 * @api
 */
interface InputInterface
{
    public function toArray(): array;
}

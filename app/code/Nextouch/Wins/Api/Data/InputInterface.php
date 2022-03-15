<?php
declare(strict_types=1);

namespace Nextouch\Wins\Api\Data;

/**
 * @api
 */
interface InputInterface
{
    public function toArray(): array;
}

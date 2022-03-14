<?php
declare(strict_types=1);

namespace Nextouch\Wins\Api\Data;

/**
 * @api
 */
interface ArrayableInterface
{
    public static function fromArray(array $data): self;

    public function toArray(): array;
}

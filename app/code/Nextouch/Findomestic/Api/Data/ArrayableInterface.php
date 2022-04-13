<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Api\Data;

/**
 * @api
 */
interface ArrayableInterface
{
    public static function fromArray(array $data): self;

    public function toArray(): array;
}

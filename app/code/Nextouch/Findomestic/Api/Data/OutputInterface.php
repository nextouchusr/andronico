<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Api\Data;

/**
 * @api
 */
interface OutputInterface
{
    public static function fromArray(array $data): self;

    public static function fromError(array $data): self;
}

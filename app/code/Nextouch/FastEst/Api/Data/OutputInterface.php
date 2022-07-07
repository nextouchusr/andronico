<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api\Data;

/**
 * @api
 */
interface OutputInterface
{
    public static function fromObject(\stdClass $object): self;
}

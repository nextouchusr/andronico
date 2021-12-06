<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api\Data;

/**
 * @api
 */
interface InputInterface
{
    public function asObject(): \stdClass;
}

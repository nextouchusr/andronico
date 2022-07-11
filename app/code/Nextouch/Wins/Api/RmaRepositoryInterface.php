<?php
declare(strict_types=1);

namespace Nextouch\Wins\Api;

use Nextouch\Wins\Model\Request\Rma\CreateReturn;

/**
 * @api
 */
interface RmaRepositoryInterface
{
    public function create(CreateReturn $request): bool;
}

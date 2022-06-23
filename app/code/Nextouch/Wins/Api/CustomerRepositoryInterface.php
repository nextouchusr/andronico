<?php
declare(strict_types=1);

namespace Nextouch\Wins\Api;

use Nextouch\Wins\Model\Request\Customer\UpsertCustomer;

/**
 * @api
 */
interface CustomerRepositoryInterface
{
    public function upsert(UpsertCustomer $request): bool;
}

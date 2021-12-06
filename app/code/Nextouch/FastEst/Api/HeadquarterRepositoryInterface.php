<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Model\Response\Directory\HeadquarterListResponse;

/**
 * @api
 */
interface HeadquarterRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): HeadquarterListResponse;
}

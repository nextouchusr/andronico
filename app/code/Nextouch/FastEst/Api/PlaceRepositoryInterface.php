<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Model\Response\Directory\PlaceListResponse;

/**
 * @api
 */
interface PlaceRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): PlaceListResponse;
}

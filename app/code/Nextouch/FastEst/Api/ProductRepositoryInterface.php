<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Model\Response\Directory\ProductListResponse;

/**
 * @api
 */
interface ProductRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): ProductListResponse;
}

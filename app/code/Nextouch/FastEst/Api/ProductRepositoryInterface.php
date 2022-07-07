<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Response\Directory\GetProductList;

/**
 * @api
 */
interface ProductRepositoryInterface
{
    /**
     * @return \Nextouch\FastEst\Model\Response\Directory\GetProductList
     * @throws \Collections\Exceptions\InvalidArgumentException
     */
    public function getAll(): GetProductList;
}

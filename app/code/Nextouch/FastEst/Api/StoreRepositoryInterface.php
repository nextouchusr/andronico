<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Response\Directory\GetStoreList;

/**
 * @api
 */
interface StoreRepositoryInterface
{
    /**
     * @return \Nextouch\FastEst\Model\Response\Directory\GetStoreList
     * @throws \Collections\Exceptions\InvalidArgumentException
     */
    public function getAll(): GetStoreList;
}

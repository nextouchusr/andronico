<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Response\Directory\GetVanList;

/**
 * @api
 */
interface VanRepositoryInterface
{
    /**
     * @return \Nextouch\FastEst\Model\Response\Directory\GetVanList
     * @throws \Collections\Exceptions\InvalidArgumentException
     */
    public function getAll(): GetVanList;
}

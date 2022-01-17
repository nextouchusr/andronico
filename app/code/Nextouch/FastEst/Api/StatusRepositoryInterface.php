<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Response\Directory\GetStatusList;

/**
 * @api
 */
interface StatusRepositoryInterface
{
    /**
     * @return \Nextouch\FastEst\Model\Response\Directory\GetStatusList
     * @throws \Collections\Exceptions\InvalidArgumentException
     */
    public function getAll(): GetStatusList;
}

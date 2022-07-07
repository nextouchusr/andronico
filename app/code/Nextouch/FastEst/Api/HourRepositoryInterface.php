<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Response\Directory\GetHourList;

/**
 * @api
 */
interface HourRepositoryInterface
{
    /**
     * @return \Nextouch\FastEst\Model\Response\Directory\GetHourList
     * @throws \Collections\Exceptions\InvalidArgumentException
     */
    public function getAll(): GetHourList;
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Response\Directory\GetPlaceList;

/**
 * @api
 */
interface PlaceRepositoryInterface
{
    /**
     * @return \Nextouch\FastEst\Model\Response\Directory\GetPlaceList
     * @throws \Collections\Exceptions\InvalidArgumentException
     */
    public function getAll(): GetPlaceList;
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Response\Directory\GetHeadquarterList;

/**
 * @api
 */
interface HeadquarterRepositoryInterface
{
    /**
     * @return \Nextouch\FastEst\Model\Response\Directory\GetHeadquarterList
     * @throws \Collections\Exceptions\InvalidArgumentException
     */
    public function getAll(): GetHeadquarterList;
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Api;

use Nextouch\FastEst\Model\Response\Directory\GetSourceList;

/**
 * @api
 */
interface SourceRepositoryInterface
{
    /**
     * @return \Nextouch\FastEst\Model\Response\Directory\GetSourceList
     * @throws \Collections\Exceptions\InvalidArgumentException
     */
    public function getAll(): GetSourceList;
}

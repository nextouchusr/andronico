<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Api;

/**
 * @api
 */
interface AttributeDataProviderInterface
{
    public function fetchData(): \IteratorAggregate;
}

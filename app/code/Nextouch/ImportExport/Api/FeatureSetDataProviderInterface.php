<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Api;

/**
 * @api
 */
interface FeatureSetDataProviderInterface
{
    public function fetchData(): \IteratorAggregate;
}

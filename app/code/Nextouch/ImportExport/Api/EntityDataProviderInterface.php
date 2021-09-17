<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Api;

use Magento\Framework\Exception\LocalizedException;

/**
 * @api
 */
interface EntityDataProviderInterface
{
    /**
     * @throws LocalizedException
     */
    public function fetchData(): \IteratorAggregate;
}

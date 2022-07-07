<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Api;

use Magento\Framework\Exception\LocalizedException;

/**
 * @api
 */
interface EntityDataImportInterface
{
    /**
     * @throws LocalizedException
     */
    public function importData(\IteratorAggregate $data): void;
}

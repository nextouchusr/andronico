<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Api;

/**
 * @api
 */
interface AttributeDataImportInterface
{
    public function importData(\IteratorAggregate $data): void;
}

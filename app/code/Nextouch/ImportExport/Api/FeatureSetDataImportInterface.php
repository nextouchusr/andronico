<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Api;

/**
 * @api
 */
interface FeatureSetDataImportInterface
{
    public function importData(\IteratorAggregate $data): void;
}

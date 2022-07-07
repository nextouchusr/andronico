<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Api;

/**
 * @api
 */
interface EntityDataOperationInterface
{
    public function run(): void;
}

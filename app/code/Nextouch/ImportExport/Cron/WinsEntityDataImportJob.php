<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Cron;

use Nextouch\ImportExport\Model\WinsEntityDataOperationCombiner;

class WinsEntityDataImportJob
{
    private WinsEntityDataOperationCombiner $entityDataOperationCombiner;

    public function __construct(WinsEntityDataOperationCombiner $entityDataOperationCombiner)
    {
        $this->entityDataOperationCombiner = $entityDataOperationCombiner;
    }

    public function execute(): void
    {
        $this->entityDataOperationCombiner->run();
    }
}

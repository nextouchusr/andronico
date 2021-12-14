<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Cron;

use Magento\Framework\App\Area;
use Magento\Store\Model\App\Emulation;
use Nextouch\ImportExport\Model\WinsEntityDataOperationCombiner;

class WinsEntityDataImportJob
{
    private const STORE_CODE = 'en_GB';

    private WinsEntityDataOperationCombiner $entityDataOperationCombiner;
    private Emulation $emulation;

    public function __construct(WinsEntityDataOperationCombiner $entityDataOperationCombiner, Emulation $emulation)
    {
        $this->entityDataOperationCombiner = $entityDataOperationCombiner;
        $this->emulation = $emulation;
    }

    public function execute(): void
    {
        $this->emulation->startEnvironmentEmulation(self::STORE_CODE, Area::AREA_ADMINHTML, true);

        $this->entityDataOperationCombiner->run();

        $this->emulation->stopEnvironmentEmulation();
    }
}

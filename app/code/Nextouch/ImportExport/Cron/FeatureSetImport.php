<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Cron;

use Nextouch\ImportExport\Api\FeatureSetDataImportInterface;
use Nextouch\ImportExport\Api\FeatureSetDataProviderInterface;

class FeatureSetImport
{
    private FeatureSetDataProviderInterface $featureSetDataProvider;
    private FeatureSetDataImportInterface $featureSetDataImport;

    public function __construct(
        FeatureSetDataProviderInterface $featureSetDataProvider,
        FeatureSetDataImportInterface $featureSetDataImport
    ) {
        $this->featureSetDataProvider = $featureSetDataProvider;
        $this->featureSetDataImport = $featureSetDataImport;
    }

    public function execute(): void
    {
        $data = $this->featureSetDataProvider->fetchData();
        $this->featureSetDataImport->importData($data);
    }
}

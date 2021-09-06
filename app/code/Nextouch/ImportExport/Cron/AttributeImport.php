<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Cron;

use Nextouch\ImportExport\Api\AttributeDataImportInterface;
use Nextouch\ImportExport\Api\AttributeDataProviderInterface;

class AttributeImport
{
    private AttributeDataProviderInterface $attributeDataProvider;
    private AttributeDataImportInterface $attributeDataImport;

    public function __construct(
        AttributeDataProviderInterface $attributeDataProvider,
        AttributeDataImportInterface $attributeDataImport
    ) {
        $this->attributeDataProvider = $attributeDataProvider;
        $this->attributeDataImport = $attributeDataImport;
    }

    public function execute(): void
    {
        $data = $this->attributeDataProvider->fetchData();
        $this->attributeDataImport->importData($data);
    }
}

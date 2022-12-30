<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Plugin\Model\Import\Product\Type;

use Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType;

class DisableProductsWithNoPrice
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforePrepareAttributesWithDefaultValueForSave(
        AbstractType $subject,
        array $rowData,
        bool $withDefaultValue
    ): array {
        if ($this->canDisableProduct($rowData)) {
            $rowData['visibility'] = '';
        }

        return [$rowData, $withDefaultValue];
    }

    private function canDisableProduct(array $rowData): bool
    {
        return !isset($rowData['price']) || (float) $rowData['price'] <= 0;
    }
}

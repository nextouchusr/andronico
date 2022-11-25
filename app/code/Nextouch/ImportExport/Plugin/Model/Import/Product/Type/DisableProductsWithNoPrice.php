<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Plugin\Model\Import\Product\Type;

use Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType;

class DisableProductsWithNoPrice
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterPrepareAttributesWithDefaultValueForSave(
        AbstractType $subject,
        array $result,
        array $rowData
    ): array {
        if ($this->canDisableProduct($rowData)) {
            $result['visibility'] = '';
        }

        return $result;
    }

    private function canDisableProduct(array $rowData): bool
    {
        return !isset($rowData['price']) || (float) $rowData['price'] <= 0;
    }
}

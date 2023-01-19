<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Plugin\Model\Import\Product\Type;

use Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType;
use Nextouch\Catalog\Api\Data\ProductInterface;

class SetFastEstTypeDefaultValue
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterPrepareAttributesWithDefaultValueForSave(
        AbstractType $subject,
        array $result,
        array $rowData
    ): array {
        if (!isset($rowData['fast_est_type'])) {
            return $result;
        }

        $result[ProductInterface::FAST_EST_TYPE] = $this->getFastEstType($rowData);

        return $result;
    }

    private function getFastEstType(array $rowData): string
    {
        if ($rowData['fast_est_type'] === 'Da verificare') {
            return ProductInterface::DEFAULT_FAST_EST_TYPE['code'];
        }

        return (string) $rowData['fast_est_type'];
    }
}

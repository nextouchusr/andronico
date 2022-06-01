<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Plugin\Model\Import\Product\Type;

use Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType;
use Nextouch\Catalog\Api\Data\ProductInterface;

class PrepareIsReturnableValue
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterPrepareAttributesWithDefaultValueForSave(
        AbstractType $subject,
        array $result,
        array $rowData
    ): array {
        if (!isset($rowData['enable_rma'])) {
            return $result;
        }

        $result[ProductInterface::IS_RETURNABLE] = (int) $rowData['enable_rma'];

        return $result;
    }
}

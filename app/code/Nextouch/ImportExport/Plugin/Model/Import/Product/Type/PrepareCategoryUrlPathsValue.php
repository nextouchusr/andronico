<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Plugin\Model\Import\Product\Type;

use Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType;
use function Symfony\Component\String\u;

class PrepareCategoryUrlPathsValue
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforePrepareAttributesWithDefaultValueForSave(
        AbstractType $subject,
        array $rowData,
        bool $withDefaultValue = true
    ): array {
        if (!isset($rowData['categories'])) {
            return [$rowData, $withDefaultValue];
        }

        $rowData['category_url_paths'] = u($rowData['categories'])
            ->lower()
            ->replace(' ', '-')
            ->replaceMatches('/[^A-Za-z0-9-\/]+/', '')
            ->toString();

        return [$rowData, $withDefaultValue];
    }
}

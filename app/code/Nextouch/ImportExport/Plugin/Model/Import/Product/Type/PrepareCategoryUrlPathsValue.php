<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Plugin\Model\Import\Product\Type;

use Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType;
use Nextouch\Catalog\Api\Data\CategoryInterface;
use function Lambdish\Phunctional\map;
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

        $categories = explode(CategoryInterface::PATH_SEPARATOR, $rowData['categories']);
        $slugifyCategories = map(fn(string $item) => $this->slugifyCategory($item), $categories);
        $categoryUrlPaths = implode(CategoryInterface::PATH_SEPARATOR, $slugifyCategories);

        $rowData['category_url_paths'] = $categoryUrlPaths;

        return [$rowData, $withDefaultValue];
    }

    private function slugifyCategory(string $category): string
    {
        return u($category)
            ->replaceMatches('/[^A-Za-z0-9-]+/', '-')
            ->trim('-')
            ->lower()
            ->toString();
    }
}

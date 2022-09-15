<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Plugin\Model\Import\Product\Type;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Catalog\Api\Data\CategoryInterface;
use Nextouch\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Psr\Log\LoggerInterface;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\reduce;

class AssociateExternalCategoryId
{
    private CategoryCollectionFactory $categoryCollectionFactory;
    private CategoryRepositoryInterface $categoryRepository;
    private LoggerInterface $logger;

    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        CategoryRepositoryInterface $categoryRepository,
        LoggerInterface $logger
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryRepository = $categoryRepository;
        $this->logger = $logger;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterPrepareAttributesWithDefaultValueForSave(
        AbstractType $subject,
        array $result,
        array $rowData
    ): array {
        if (!$this->canExtractCategoryRawData($rowData)) {
            return $result;
        }

        foreach ($this->explodeRowData($rowData) as $data) {
            $categories = $this->extractCategoryRawData($data);

            each(fn(array $item) => $this->associateExternalCategoryId($item), $categories);
        }

        return $result;
    }

    private function canExtractCategoryRawData(array $rowData): bool
    {
        return isset($rowData['category_paths']) && isset($rowData['category_url_paths']);
    }

    private function explodeRowData(array $rowData): array
    {
        $categories = explode(CategoryInterface::CATEGORY_SEPARATOR, $rowData['categories']);
        $categoryPaths = explode(CategoryInterface::CATEGORY_SEPARATOR, $rowData['category_paths']);
        $categoryUrlPaths = explode(CategoryInterface::CATEGORY_SEPARATOR, $rowData['category_url_paths']);

        return reduce(function (array $acc, string $category, int $index) use ($categoryPaths, $categoryUrlPaths) {
            $rowData = [
                'category_paths' => $categoryPaths[$index],
                'category_url_paths' => $categoryUrlPaths[$index],
            ];

            return [...$acc, $rowData];
        }, $categories, []);
    }

    private function extractCategoryRawData(array $rowData): array
    {
        $categoryPaths = explode(CategoryInterface::PATH_SEPARATOR, $rowData['category_paths']);
        $categoryUrlPaths = explode(CategoryInterface::PATH_SEPARATOR, $rowData['category_url_paths']);

        return reduce(function (array $acc, string $id, int $index) use ($categoryUrlPaths) {
            if ($categoryUrlPaths[$index] === CategoryInterface::ROOT_CATEGORY_PATH) {
                return $acc;
            }

            $urlPath = implode(CategoryInterface::PATH_SEPARATOR, array_slice($categoryUrlPaths, 1, $index));
            $category = ['id' => $id, 'url_path' => $urlPath];

            return [...$acc, $category];
        }, $categoryPaths, []);
    }

    private function associateExternalCategoryId(array $rawData): void
    {
        try {
            /** @var CategoryInterface $category */
            $category = $this->categoryCollectionFactory
                ->create()
                ->addUrlPathToFilter($rawData['url_path'])
                ->getFirstItem();

            if (!$category->getId()) {
                throw new NoSuchEntityException(__('The category that was requested does not exist.'));
            }

            $category->setExternalCategoryId($rawData['id']);

            $this->categoryRepository->save($category);
        } catch (LocalizedException $e) {
            $text = 'Failed to associate external category id %1 to the category %2. Error: %3';
            $message = __($text, $rawData['id'], $rawData['url_path'], $e->getMessage());
            $this->logger->error($message);
        }
    }
}

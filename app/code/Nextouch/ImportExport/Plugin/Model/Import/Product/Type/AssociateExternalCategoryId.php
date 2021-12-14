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
use function Lambdish\Phunctional\map;

class AssociateExternalCategoryId
{
    private const CATEGORY_SEPARATOR = '/';

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

        $categories = $this->extractCategoryRawData($rowData);

        \Lambdish\Phunctional\each(fn(array $item) => $this->associateExternalCategoryId($item), $categories);

        return $result;
    }

    private function canExtractCategoryRawData(array $rowData): bool
    {
        return isset($rowData['category_ids_tree']) && isset($rowData['categories']);
    }

    private function extractCategoryRawData(array $rowData): array
    {
        $categoryIds = explode(self::CATEGORY_SEPARATOR, $rowData['category_ids_tree']);
        $categoryNames = explode(self::CATEGORY_SEPARATOR, $rowData['categories']);

        return map(fn(string $id, int $index) => ['id' => $id, 'name' => $categoryNames[$index]], $categoryIds);
    }

    private function associateExternalCategoryId(array $rawData): void
    {
        try {
            /** @var CategoryInterface $category */
            $category = $this->categoryCollectionFactory
                ->create()
                ->addNameToFilter($rawData['name'])
                ->getFirstItem();

            if (!$category->getId()) {
                throw new NoSuchEntityException(__('The category that was requested does not exist.'));
            }

            $category->setExternalCategoryId($rawData['id']);

            $this->categoryRepository->save($category);
        } catch (LocalizedException $e) {
            $text = 'Failed to associate external category id %1 to the category %2. Error: %3';
            $message = __($text, $rawData['id'], $rawData['name'], $e->getMessage());
            $this->logger->error($message);
        }
    }
}

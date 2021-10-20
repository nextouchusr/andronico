<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Plugin\Model\Import\Product\Type;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Catalog\Api\Data\ProductInterface;
use Nextouch\Eav\Api\AttributeOptionManagementInterface;
use Psr\Log\LoggerInterface;

class PrepareBrandAttributeValue
{
    private ProductAttributeRepositoryInterface $attributeRepository;
    private AttributeOptionManagementInterface $attributeOptionManagement;
    private LoggerInterface $logger;

    public function __construct(
        ProductAttributeRepositoryInterface $attributeRepository,
        AttributeOptionManagementInterface $attributeOptionManagement,
        LoggerInterface $logger
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeOptionManagement = $attributeOptionManagement;
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
        try {
            if (!isset($rowData['manufacturer'])) {
                return $result;
            }

            $attribute = $this->attributeRepository->get(ProductInterface::BRAND);
            $attributeOption = $this->attributeOptionManagement->getByExternalOptionId(
                (int) $attribute->getAttributeId(),
                $rowData['manufacturer']
            );

            $result[ProductInterface::BRAND] = $attributeOption->getOptionId();
        } catch (NoSuchEntityException $e) {
            $text = 'Failed to assign brand %1 to the product %2. Error: %3';
            $message = __($text, $rowData['manufacturer'], $rowData['sku'], $e->getMessage());
            $this->logger->error($message);
        }

        return $result;
    }
}

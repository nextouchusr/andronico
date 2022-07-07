<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Product\Attribute;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Catalog\Api\Data\ProductInterface;
use Nextouch\Eav\Api\AttributeOptionManagementInterface;
use Nextouch\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Nextouch\ImportExport\Api\EntityDataImportInterface;
use Nextouch\ImportExport\Model\Wins\Brand;
use Psr\Log\LoggerInterface;

class WinsBrandDataImport implements EntityDataImportInterface
{
    private ProductAttributeRepositoryInterface $attributeRepository;
    private AttributeOptionInterfaceFactory $attributeOptionFactory;
    private AttributeOptionManagementInterface $attributeOptionManagement;
    private LoggerInterface $logger;

    public function __construct(
        ProductAttributeRepositoryInterface $attributeRepository,
        AttributeOptionInterfaceFactory $attributeOptionFactory,
        AttributeOptionManagementInterface $attributeOptionManagement,
        LoggerInterface $logger
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeOptionFactory = $attributeOptionFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->logger = $logger;
    }

    public function importData(\IteratorAggregate $data): void
    {
        try {
            $attribute = $this->attributeRepository->get(ProductInterface::BRAND);
            \Lambdish\Phunctional\each(fn(Brand $item) => $this->saveAttributeOption($attribute, $item), $data);
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getCode());
        }
    }

    private function saveAttributeOption(AttributeInterface $attribute, Brand $value): void
    {
        try {
            $attributeOption = $this->attributeOptionManagement->getByExternalOptionId(
                ProductAttributeInterface::ENTITY_TYPE_CODE,
                $attribute->getAttributeCode(),
                $value->getCode()
            );
            $attributeOption->setLabel($value->getDescription());
        } catch (NoSuchEntityException $e) {
            $attributeOption = $this->attributeOptionFactory->create();
            $attributeOption->setLabel($value->getDescription());
            $attributeOption->setExternalOptionId($value->getCode());
        }

        try {
            $this->attributeOptionManagement->save(
                ProductAttributeInterface::ENTITY_TYPE_CODE,
                $attribute->getAttributeCode(),
                $attributeOption
            );
        } catch (LocalizedException $e) {
            $message = __('Failed to import brand %1. Error: %2', $value->getDescription(), $e->getMessage());
            $this->logger->error($message);
        }
    }
}

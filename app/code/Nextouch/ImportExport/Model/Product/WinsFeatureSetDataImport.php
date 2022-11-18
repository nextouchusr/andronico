<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Product;

use Magento\Framework\Exception\LocalizedException;
use Nextouch\Catalog\Api\ProductRepositoryInterface;
use Nextouch\ImportExport\Api\EntityDataImportInterface;
use Nextouch\ImportExport\Model\Wins\FeatureSet;
use Nextouch\ImportExport\Model\Wins\Product\AttributeValue\AttributeValueMapper;
use Psr\Log\LoggerInterface;

class WinsFeatureSetDataImport implements EntityDataImportInterface
{
    private ProductRepositoryInterface $productRepository;
    private AttributeValueMapper $attributeValueMapper;
    private LoggerInterface $logger;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        AttributeValueMapper $attributeValueMapper,
        LoggerInterface $logger
    ) {
        $this->productRepository = $productRepository;
        $this->attributeValueMapper = $attributeValueMapper;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function importData(\IteratorAggregate $data): void
    {
        \Lambdish\Phunctional\each(fn(FeatureSet $item) => $this->saveProductFeatureSet($item), $data);
    }

    private function saveProductFeatureSet(FeatureSet $featureSet): void
    {
        try {
            $customAttributes = $this->attributeValueMapper->mapCollection($featureSet->getFeatures());

            $product = $this->productRepository->getByEcatCode($featureSet->getProductCode());
            $product->setCustomAttributes($customAttributes);
            $this->productRepository->save($product);
        } catch (LocalizedException $e) {
            $text = 'Failed to import feature set for the product %1. Error: %2';
            $message = __($text, $featureSet->getProductCode(), $e->getMessage());
            $this->logger->error($message);
        }
    }
}

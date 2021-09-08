<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\ImportExport\Api\FeatureSetDataImportInterface;
use Nextouch\ImportExport\Model\Wins\FeatureSet;
use Nextouch\ImportExport\Model\Wins\Product\AttributeValue\AttributeValueMapper;
use Psr\Log\LoggerInterface;

class WinsFeatureSetDataImport implements FeatureSetDataImportInterface
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

    public function importData(\IteratorAggregate $data): void
    {
        \Lambdish\Phunctional\each(fn(FeatureSet $item) => $this->saveProductFeatureSet($item), $data);
    }

    private function saveProductFeatureSet(FeatureSet $featureSet): void
    {
        try {
            $customAttributes = $this->attributeValueMapper->mapCollection($featureSet->getFeatures());

            $product = $this->productRepository->get($featureSet->getProductCode());
            $product->setCustomAttributes($customAttributes);
            $this->productRepository->save($product);
        } catch (LocalizedException $e) {
            $text = 'Failed to import feature set for the product %1. Error: %2';
            $message = __($text, $featureSet->getProductCode(), $e->getMessage());
            $this->logger->error($message);
        }
    }
}

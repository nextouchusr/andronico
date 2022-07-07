<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Product\AttributeValue;

use Collections\Collection;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Api\AttributeValue;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Eav\Api\AttributeOptionManagementInterface;
use Nextouch\ImportExport\Model\Wins\Feature;
use Psr\Log\LoggerInterface;
use function Lambdish\Phunctional\reduce;

class AttributeValueMapper
{
    private ProductAttributeRepositoryInterface $attributeRepository;
    private AttributeOptionManagementInterface $attributeOptionManagement;
    private AttributeValueFactory $attributeValueFactory;
    private LoggerInterface $logger;

    public function __construct(
        ProductAttributeRepositoryInterface $attributeRepository,
        AttributeOptionManagementInterface $attributeOptionManagement,
        AttributeValueFactory $attributeValueFactory,
        LoggerInterface $logger
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->attributeValueFactory = $attributeValueFactory;
        $this->logger = $logger;
    }

    /**
     * @return AttributeValue[]
     */
    public function mapCollection(Collection $features): array
    {
        $callback = function (array $acc, Feature $feature) {
            try {
                $acc[$feature->getCode()] = $this->map($feature);
            } catch (LocalizedException $e) {
                $this->logger->error($e->getMessage());
            }

            return $acc;
        };

        return reduce($callback, $features, []);
    }

    /**
     * @throws LocalizedException
     */
    public function map(Feature $feature): AttributeValue
    {
        $attributeValue = $this->attributeValueFactory->create();
        $attributeValue->setAttributeCode($feature->getCode());
        $attributeValue->setValue($this->fetchValue($feature));

        return $attributeValue;
    }

    /**
     * @throws LocalizedException
     */
    private function fetchValue(Feature $feature): string
    {
        try {
            if ($feature->hasValueCode()) {
                $attribute = $this->attributeRepository->get($feature->getCode());

                $attributeOption = $this->attributeOptionManagement->getByExternalOptionId(
                    ProductAttributeInterface::ENTITY_TYPE_CODE,
                    $attribute->getAttributeCode(),
                    $feature->getValueCode()
                );

                return (string) $attributeOption->getOptionId();
            }

            return $feature->getValue();
        } catch (LocalizedException $e) {
            $message = __('Failed to map feature %1. Error: %2', $feature->getDescription(), $e->getMessage());
            throw new LocalizedException($message);
        }
    }
}

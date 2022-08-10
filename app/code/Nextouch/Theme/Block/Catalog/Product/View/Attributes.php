<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Catalog\Product\View;

use Magento\Framework\Phrase;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Nextouch\Eav\Api\AttributeGroupRepositoryInterface;

class Attributes extends \Magento\Catalog\Block\Product\View\Attributes
{
    private AttributeGroupRepositoryInterface $attributeGroupRepository;

    public function __construct(
        Context $context,
        Registry $registry,
        PriceCurrencyInterface $priceCurrency,
        AttributeGroupRepositoryInterface $attributeGroupRepository,
        array $data = []
    ) {
        parent::__construct($context, $registry, $priceCurrency, $data);
        $this->attributeGroupRepository = $attributeGroupRepository;
    }

    public function getAdditionalData(array $excludeAttr = []): array
    {
        $data = [];
        $product = $this->getProduct();
        $attributeSetId = (int) $product->getAttributeSetId();
        $attributeGroups = $this->attributeGroupRepository->getItemsByAttributeSetId($attributeSetId);

        foreach ($attributeGroups as $attributeGroup) {
            $attributes = $product->getAttributes($attributeGroup->getAttributeGroupId());

            foreach ($attributes as $attribute) {
                if ($this->isVisibleOnFrontend($attribute, $excludeAttr)) {
                    $value = $attribute->getFrontend()->getValue($product);

                    if ($value instanceof Phrase) {
                        $value = (string) $value;
                    } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
                        $value = $this->priceCurrency->convertAndFormat($value);
                    }

                    if (is_string($value) && strlen(trim($value))) {
                        $attributesGroup = $data[$attributeGroup->getAttributeGroupId()]['attributes'] ?? [];

                        $data[$attributeGroup->getAttributeGroupId()] = [
                            'label' => $attributeGroup->getAttributeGroupName(),
                            'attributes' => array_merge($attributesGroup, [
                                $attribute->getAttributeCode() => [
                                    'label' => $attribute->getStoreLabel(),
                                    'value' => $value,
                                    'code' => $attribute->getAttributeCode(),
                                ],
                            ]),
                        ];
                    }
                }
            }
        }

        return $data;
    }
}

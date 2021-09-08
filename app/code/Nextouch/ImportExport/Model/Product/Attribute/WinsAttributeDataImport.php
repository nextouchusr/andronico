<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Product\Attribute;

use Magento\Catalog\Api\AttributeSetManagementInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\ProductAttributeManagementInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Eav\Api\Data\AttributeGroupInterface;
use Magento\Eav\Api\Data\AttributeGroupInterfaceFactory;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Eav\Api\AttributeGroupRepositoryInterface;
use Nextouch\Eav\Api\AttributeOptionManagementInterface;
use Nextouch\Eav\Api\AttributeSetRepositoryInterface;
use Nextouch\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Nextouch\Eav\Api\Data\AttributeSetInterfaceFactory;
use Nextouch\ImportExport\Api\AttributeDataImportInterface;
use Nextouch\ImportExport\Model\Wins\Group;
use Nextouch\ImportExport\Model\Wins\Product\Attribute\AttributeMapperFactory;
use Nextouch\ImportExport\Model\Wins\Property;
use Nextouch\ImportExport\Model\Wins\Property\SelectableProperty;
use Nextouch\ImportExport\Model\Wins\PropertyValue;
use Nextouch\ImportExport\Model\Wins\Template;
use Psr\Log\LoggerInterface;

class WinsAttributeDataImport implements AttributeDataImportInterface
{
    private const ATTRIBUTE_SET_SKELETON_ID = 4;

    private AttributeSetInterfaceFactory $attributeSetFactory;
    private AttributeSetRepositoryInterface $attributeSetRepository;
    private AttributeSetManagementInterface $attributeSetManagement;
    private AttributeGroupInterfaceFactory $attributeGroupFactory;
    private AttributeGroupRepositoryInterface $attributeGroupRepository;
    private AttributeMapperFactory $attributeMapperFactory;
    private ProductAttributeRepositoryInterface $attributeRepository;
    private ProductAttributeManagementInterface $attributeManagement;
    private AttributeOptionInterfaceFactory $attributeOptionFactory;
    private AttributeOptionManagementInterface $attributeOptionManagement;
    private LoggerInterface $logger;

    public function __construct(
        AttributeSetInterfaceFactory $attributeSetFactory,
        AttributeSetRepositoryInterface $attributeSetRepository,
        AttributeSetManagementInterface $attributeSetManagement,
        AttributeGroupInterfaceFactory $attributeGroupFactory,
        AttributeGroupRepositoryInterface $attributeGroupRepository,
        AttributeMapperFactory $attributeMapperFactory,
        ProductAttributeRepositoryInterface $attributeRepository,
        ProductAttributeManagementInterface $attributeManagement,
        AttributeOptionInterfaceFactory $attributeOptionFactory,
        AttributeOptionManagementInterface $attributeOptionManagement,
        LoggerInterface $logger
    ) {
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->attributeSetManagement = $attributeSetManagement;
        $this->attributeGroupFactory = $attributeGroupFactory;
        $this->attributeGroupRepository = $attributeGroupRepository;
        $this->attributeMapperFactory = $attributeMapperFactory;
        $this->attributeRepository = $attributeRepository;
        $this->attributeManagement = $attributeManagement;
        $this->attributeOptionFactory = $attributeOptionFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->logger = $logger;
    }

    public function importData(\IteratorAggregate $data): void
    {
        \Lambdish\Phunctional\each(fn(Template $item) => $this->saveAttributeSet($item), $data);
    }

    private function saveAttributeSet(Template $template): void
    {
        try {
            $attributeSet = $this->attributeSetRepository->getByExternalSetId($template->getCode());
            $attributeSet->setAttributeSetName($template->getDescription());
            $attributeSet = $this->attributeSetRepository->save($attributeSet);
        } catch (LocalizedException $e) {
            $attributeSet = $this->attributeSetFactory->create();
            $attributeSet->setAttributeSetName($template->getDescription());
            $attributeSet->setExternalSetId($template->getCode());
            $attributeSet = $this->attributeSetManagement->create($attributeSet, self::ATTRIBUTE_SET_SKELETON_ID);
        }

        $this->saveAttributeGroups($attributeSet, $template->getGroups());
    }

    private function saveAttributeGroups(AttributeSetInterface $attributeSet, \IteratorAggregate $groups): void
    {
        \Lambdish\Phunctional\each(fn(Group $item) => $this->saveAttributeGroup($attributeSet, $item), $groups);
    }

    private function saveAttributeGroup(AttributeSetInterface $attributeSet, Group $group): void
    {
        try {
            $attributeGroup = $this->attributeGroupRepository->getByAttributeGroupCode(
                (int) $attributeSet->getAttributeSetId(),
                $group->getCode()
            );
            $attributeGroup->setAttributeGroupName($group->getDescription());
        } catch (NoSuchEntityException $e) {
            $attributeGroup = $this->attributeGroupFactory->create();
            $attributeGroup->setAttributeGroupName($group->getDescription());
            $attributeGroup->setAttributeSetId($attributeSet->getAttributeSetId());
        }

        try {
            $attributeGroup = $this->attributeGroupRepository->save($attributeGroup);
            $this->saveAttributes($attributeGroup, $group->getProperties());
        } catch (LocalizedException $e) {
            $message = __('Failed to import attribute group %1. Error: %2', $group->getDescription(), $e->getMessage());
            $this->logger->error($message);
        }
    }

    private function saveAttributes(AttributeGroupInterface $attributeGroup, \IteratorAggregate $properties): void
    {
        \Lambdish\Phunctional\each(fn(Property $item) => $this->saveAttribute($attributeGroup, $item), $properties);
    }

    private function saveAttribute(AttributeGroupInterface $attributeGroup, Property $property): void
    {
        try {
            $attribute = $this->attributeRepository->get($property->getCode());
            $attribute->setDefaultFrontendLabel($property->getDescription());
        } catch (NoSuchEntityException $e) {
            $attributeMapper = $this->attributeMapperFactory->create($property);
            $attribute = $attributeMapper->map($property);
        }

        try {
            $attribute = $this->attributeRepository->save($attribute);

            $this->attributeManagement->assign(
                $attributeGroup->getAttributeSetId(),
                $attributeGroup->getAttributeGroupId(),
                $attribute->getAttributeCode(),
                $property->getSortOrder()
            );

            if ($property instanceof SelectableProperty) {
                $this->saveAttributeOptions($attribute, $property->getValues());
            }
        } catch (LocalizedException $e) {
            $message = __('Failed to import attribute %1. Error: %2', $property->getDescription(), $e->getMessage());
            $this->logger->error($message);
        }
    }

    private function saveAttributeOptions(AttributeInterface $attribute, \IteratorAggregate $values): void
    {
        \Lambdish\Phunctional\each(fn(PropertyValue $item) => $this->saveAttributeOption($attribute, $item), $values);
    }

    private function saveAttributeOption(AttributeInterface $attribute, PropertyValue $value): void
    {
        try {
            $attributeOption = $this->attributeOptionManagement->getByExternalOptionId(
                (int) $attribute->getAttributeId(),
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
            $text = 'Failed to import option %1 for the attribute %2. Error: %3';
            $message = __($text, $value->getDescription(), $value->getProperty()->getDescription(), $e->getMessage());
            $this->logger->error($message);
        }
    }
}

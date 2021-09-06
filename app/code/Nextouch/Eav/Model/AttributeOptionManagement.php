<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model;

use Magento\Eav\Api\AttributeOptionUpdateInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option as AttributeOptionResourceModel;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nextouch\Eav\Api\AttributeOptionManagementInterface;
use Nextouch\Eav\Api\Data\AttributeOptionInterface;
use Nextouch\Eav\Model\ResourceModel\AttributeOption\CollectionFactory as AttributeOptionCollectionFactory;

class AttributeOptionManagement implements AttributeOptionManagementInterface
{
    private AttributeOptionResourceModel $attributeOptionResourceModel;
    private AttributeOptionCollectionFactory $attributeOptionCollectionFactory;
    private \Magento\Eav\Api\AttributeOptionManagementInterface $attributeOptionManagement;
    private AttributeOptionUpdateInterface $attributeOptionUpdate;

    public function __construct(
        AttributeOptionResourceModel $attributeOptionResourceModel,
        AttributeOptionCollectionFactory $attributeOptionCollectionFactory,
        \Magento\Eav\Api\AttributeOptionManagementInterface $attributeOptionManagement,
        AttributeOptionUpdateInterface $attributeOptionUpdate
    ) {
        $this->attributeOptionResourceModel = $attributeOptionResourceModel;
        $this->attributeOptionCollectionFactory = $attributeOptionCollectionFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->attributeOptionUpdate = $attributeOptionUpdate;
    }

    /**
     * {@inheritDoc}
     */
    public function getByExternalOptionId(int $attributeId, string $externalOptionId): AttributeOptionInterface
    {
        /** @var AttributeOptionInterface $attributeOption */
        $attributeOption = $this->attributeOptionCollectionFactory
            ->create()
            ->addFieldToFilter(AttributeOptionInterface::ATTRIBUTE_ID, $attributeId)
            ->addFieldToFilter(AttributeOptionInterface::EXTERNAL_OPTION_ID, $externalOptionId)
            ->getFirstItem();

        if (!$attributeOption->getOptionId()) {
            throw new NoSuchEntityException(__('Attribute option does not exist.'));
        }

        return $attributeOption;
    }

    /**
     * {@inheritDoc}
     */
    public function save(string $entityType, string $attributeCode, AttributeOptionInterface $option): int
    {
        try {
            if (!$option->getOptionId()) {
                return $this->add($entityType, $attributeCode, $option);
            }

            return $this->update($entityType, $attributeCode, $option);
        } catch (LocalizedException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
    }

    /**
     * @throws LocalizedException
     */
    private function add(string $entityType, string $attributeCode, AttributeOptionInterface $option): int
    {
        $optionId = (int) $this->attributeOptionManagement->add($entityType, $attributeCode, $option);

        // Save attribute option custom fields
        $option->setOptionId($optionId);
        $this->attributeOptionResourceModel->save($option);

        return $option->getOptionId();
    }

    /**
     * @throws LocalizedException
     */
    private function update(string $entityType, string $attributeCode, AttributeOptionInterface $option): int
    {
        $this->attributeOptionUpdate->update(
            $entityType,
            $attributeCode,
            $option->getOptionId(),
            $option
        );

        return $option->getOptionId();
    }
}

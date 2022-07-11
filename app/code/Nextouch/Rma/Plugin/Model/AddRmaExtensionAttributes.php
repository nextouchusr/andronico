<?php
declare(strict_types=1);

namespace Nextouch\Rma\Plugin\Model;

use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Api\Data\AttributeOptionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Rma\Api\Data\ItemExtensionFactory;
use Magento\Rma\Api\Data\ItemInterface;
use Magento\Rma\Api\RmaAttributesManagementInterface;
use Magento\Rma\Model\Item;
use Nextouch\Rma\Api\Data\RmaInterface;
use Nextouch\Rma\Api\RmaRepositoryInterface;
use Nextouch\Rma\Model\Rma;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\search;

class AddRmaExtensionAttributes
{
    private ItemExtensionFactory $itemExtensionFactory;
    private AttributeOptionManagementInterface $attributeOptionManagement;

    public function __construct(
        ItemExtensionFactory $itemExtensionFactory,
        AttributeOptionManagementInterface $attributeOptionManagement
    ) {
        $this->itemExtensionFactory = $itemExtensionFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetList(RmaRepositoryInterface $subject, array $returns): array
    {
        return map(fn(Rma $return) => $this->addExtensionAttributes($return), $returns);
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGet(RmaRepositoryInterface $subject, RmaInterface $return): RmaInterface
    {
        return $this->addExtensionAttributes($return);
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetByIncrementId(RmaRepositoryInterface $subject, RmaInterface $return): RmaInterface
    {
        return $this->addExtensionAttributes($return);
    }

    /**
     * @throws LocalizedException
     */
    private function addExtensionAttributes(RmaInterface $return): RmaInterface
    {
        each(function (ItemInterface $item) {
            $this->setReasonText($item);
            $this->setConditionText($item);
            $this->setResolutionText($item);
        }, $return->getItems());

        return $return;
    }

    /**
     * @throws LocalizedException
     */
    private function setReasonText(ItemInterface $item): void
    {
        /** @var AttributeOptionInterface $reason */
        $reason = search(function (AttributeOptionInterface $option) use ($item) {
            return $option->getValue() === $item->getReason();
        }, $this->getOptions(Item::REASON));

        $extensionAttributes = $item->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ?: $this->itemExtensionFactory->create();
        $extensionAttributes->setReasonText($reason->getLabel());
    }

    /**
     * @throws LocalizedException
     */
    private function setConditionText(ItemInterface $item): void
    {
        /** @var AttributeOptionInterface $condition */
        $condition = search(function (AttributeOptionInterface $option) use ($item) {
            return $option->getValue() === $item->getCondition();
        }, $this->getOptions(Item::CONDITION));

        $extensionAttributes = $item->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ?: $this->itemExtensionFactory->create();
        $extensionAttributes->setConditionText($condition->getLabel());
    }

    /**
     * @throws LocalizedException
     */
    private function setResolutionText(ItemInterface $item): void
    {
        /** @var AttributeOptionInterface $resolution */
        $resolution = search(function (AttributeOptionInterface $option) use ($item) {
            return $option->getValue() === $item->getResolution();
        }, $this->getOptions(Item::RESOLUTION));

        $extensionAttributes = $item->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ?: $this->itemExtensionFactory->create();
        $extensionAttributes->setResolutionText($resolution->getLabel());
    }

    /**
     * @throws LocalizedException
     */
    private function getOptions(string $attributeCode): array
    {
        return $this->attributeOptionManagement->getItems(
            RmaAttributesManagementInterface::ENTITY_TYPE,
            $attributeCode
        );
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Eav\Api;

use Nextouch\Eav\Api\Data\AttributeOptionInterface;

/**
 * @api
 */
interface AttributeOptionManagementInterface
{
    /**
     * @param string $entityTypeCode
     * @param string $attributeCode
     * @return \Nextouch\Eav\Api\Data\AttributeOptionInterface[]
     */
    public function getItems(string $entityTypeCode, string $attributeCode): array;

    /**
     * @param string $entityTypeCode
     * @param string $attributeCode
     * @param string $externalOptionId
     * @return \Nextouch\Eav\Api\Data\AttributeOptionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByExternalOptionId(
        string $entityTypeCode,
        string $attributeCode,
        string $externalOptionId
    ): AttributeOptionInterface;

    /**
     * @param string $entityTypeCode
     * @param string $attributeCode
     * @param \Nextouch\Eav\Api\Data\AttributeOptionInterface $option
     * @return int
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(string $entityTypeCode, string $attributeCode, AttributeOptionInterface $option): int;

    /**
     * @param string $entityTypeCode
     * @param string $attributeCode
     * @param int $optionId
     * @return bool
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(string $entityTypeCode, string $attributeCode, int $optionId): bool;
}

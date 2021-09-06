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
     * @param int $attributeId
     * @param string $externalOptionId
     * @return \Nextouch\Eav\Api\Data\AttributeOptionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByExternalOptionId(int $attributeId, string $externalOptionId): AttributeOptionInterface;

    /**
     * @param string $entityType
     * @param string $attributeCode
     * @param \Nextouch\Eav\Api\Data\AttributeOptionInterface $option
     * @return int
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(string $entityType, string $attributeCode, AttributeOptionInterface $option): int;
}

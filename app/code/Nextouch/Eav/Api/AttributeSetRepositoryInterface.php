<?php
declare(strict_types=1);

namespace Nextouch\Eav\Api;

use Nextouch\Eav\Api\Data\AttributeSetInterface;

/**
 * @api
 */
interface AttributeSetRepositoryInterface
{
    /**
     * @param string $externalSetId
     * @return \Nextouch\Eav\Api\Data\AttributeSetInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByExternalSetId(string $externalSetId): AttributeSetInterface;

    /**
     * @param \Nextouch\Eav\Api\Data\AttributeSetInterface $attributeSet
     * @return \Nextouch\Eav\Api\Data\AttributeSetInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(AttributeSetInterface $attributeSet): AttributeSetInterface;
}

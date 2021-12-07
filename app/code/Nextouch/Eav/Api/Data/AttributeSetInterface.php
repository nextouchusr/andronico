<?php
declare(strict_types=1);

namespace Nextouch\Eav\Api\Data;

/**
 * @api
 */
interface AttributeSetInterface extends \Magento\Eav\Api\Data\AttributeSetInterface
{
    public const EXTERNAL_SET_ID = 'external_set_id';

    /**
     * @return string
     */
    public function getExternalSetId(): string;

    /**
     * @param string $externalSetId
     * @return AttributeSetInterface
     */
    public function setExternalSetId(string $externalSetId): self;
}

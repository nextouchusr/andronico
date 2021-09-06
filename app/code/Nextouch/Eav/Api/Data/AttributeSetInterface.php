<?php
declare(strict_types=1);

namespace Nextouch\Eav\Api\Data;

/**
 * @api
 */
interface AttributeSetInterface extends \Magento\Eav\Api\Data\AttributeSetInterface
{
    public const EXTERNAL_SET_ID = 'external_set_id';

    public function getExternalSetId(): string;

    public function setExternalSetId(string $externalSetId): self;
}

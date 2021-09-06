<?php
declare(strict_types=1);

namespace Nextouch\Eav\Api\Data;

/**
 * @api
 */
interface AttributeOptionInterface extends \Magento\Eav\Api\Data\AttributeOptionInterface
{
    public const OPTION_ID = 'option_id';
    public const ATTRIBUTE_ID = 'attribute_id';
    public const EXTERNAL_OPTION_ID = 'external_option_id';

    public function getOptionId(): int;

    public function setOptionId(int $optionId): self;

    public function getExternalOptionId(): string;

    public function setExternalOptionId(string $externalOptionId): self;
}

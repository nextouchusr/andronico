<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model;

use Magento\Eav\Model\Entity\Attribute\Option;
use Nextouch\Eav\Api\Data\AttributeOptionInterface;

class AttributeOption extends Option implements AttributeOptionInterface
{
    public function getOptionId(): int
    {
        return (int) $this->getData(self::OPTION_ID);
    }

    public function setOptionId(int $optionId): self
    {
        $this->setData(self::OPTION_ID, $optionId);

        return $this;
    }

    public function getExternalOptionId(): string
    {
        return (string) $this->getData(self::EXTERNAL_OPTION_ID);
    }

    public function setExternalOptionId(string $externalOptionId): self
    {
        $this->setData(self::EXTERNAL_OPTION_ID, $externalOptionId);

        return $this;
    }
}

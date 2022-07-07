<?php
declare(strict_types=1);

namespace Nextouch\Eav\Model;

use Magento\Eav\Model\Entity\Attribute\Set;
use Nextouch\Eav\Api\Data\AttributeSetInterface;

class AttributeSet extends Set implements AttributeSetInterface
{
    public function getExternalSetId(): string
    {
        return (string) $this->getData(self::EXTERNAL_SET_ID);
    }

    public function setExternalSetId(string $externalSetId): self
    {
        $this->setData(self::EXTERNAL_SET_ID, $externalSetId);

        return $this;
    }
}

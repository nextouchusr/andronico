<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Directory;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Source implements OutputInterface
{
    private int $sourceId;
    private string $description;

    private function __construct(int $sourceId, string $description)
    {
        $this->sourceId = $sourceId;
        $this->description = $description;
    }

    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $sourceId = (int) $propertyAccessor->getValue($object, 'source_id');
        $description = (string) $propertyAccessor->getValue($object, 'descr');

        return new self($sourceId, $description);
    }
}

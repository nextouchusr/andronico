<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Directory;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Place implements OutputInterface
{
    public const STORE = 2;
    public const HYPOTENUSE = 11;

    private int $placeId;
    private string $description;

    private function __construct(int $placeId, string $description)
    {
        $this->placeId = $placeId;
        $this->description = $description;
    }

    public function getPlaceId(): int
    {
        return $this->placeId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $placeId = (int) $propertyAccessor->getValue($object, 'place_id');
        $description = (string) $propertyAccessor->getValue($object, 'descr');

        return new self($placeId, $description);
    }
}

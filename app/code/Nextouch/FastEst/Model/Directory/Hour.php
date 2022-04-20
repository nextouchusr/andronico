<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Directory;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Hour implements OutputInterface
{
    public const ANY = 1;

    private int $hourId;
    private string $description;

    private function __construct(int $hourId, string $description)
    {
        $this->hourId = $hourId;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getHourId(): int
    {
        return $this->hourId;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $hourId = (int) $propertyAccessor->getValue($object, 'hour_id');
        $description = (string) $propertyAccessor->getValue($object, 'descr');

        return new self($hourId, $description);
    }
}

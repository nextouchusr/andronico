<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Directory;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Status implements OutputInterface
{
    private int $statusId;
    private string $description;

    private function __construct(int $statusId, string $description)
    {
        $this->statusId = $statusId;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
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

        $statusId = (int) $propertyAccessor->getValue($object, 'status_id');
        $description = (string) $propertyAccessor->getValue($object, 'descr');

        return new self($statusId, $description);
    }
}

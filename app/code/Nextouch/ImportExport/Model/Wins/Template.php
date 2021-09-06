<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Template
{
    private string $code;
    private string $description;
    private Collection $groups;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $code, string $description, array $groups = [])
    {
        $this->code = $code;
        $this->description = $description;
        $this->groups = new Collection(Group::class, $groups);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $code = $propertyAccessor->getValue($data, '[CodiceTemplate]');
        $description = $propertyAccessor->getValue($data, '[DescrizioneTemplate]');

        return new self($code, $description);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function containsGroup(Group $group): bool
    {
        return $this->getGroups()->contains(fn(Group $item) => $item->getCode() === $group->getCode());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addGroup(Group $group): self
    {
        $group->setTemplate($this);
        $this->groups = $this->getGroups()->add($group);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addGroupIfNotExists(Group $group): self
    {
        if (!$this->containsGroup($group)) {
            return $this->addGroup($group);
        }

        return $this;
    }

    public function findGroupReference(Group $group): Group
    {
        $reference = $this->getGroups()->find(fn(Group $item) => $item->getCode() === $group->getCode());

        return $reference ?: $group;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Group
{
    private string $code;
    private ?Template $template;
    private string $description;
    private Collection $properties;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $code, string $description, array $properties = [])
    {
        $this->code = $code;
        $this->description = $description;
        $this->properties = new Collection(Property::class, $properties);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $code = $propertyAccessor->getValue($data, '[CodiceGruppo]');
        $description = $propertyAccessor->getValue($data, '[DescrizioneGruppo]');

        return new self($code, $description);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(Template $template): self
    {
        $this->template = $template;

        return $this;
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

    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function containsProperty(Property $property): bool
    {
        return $this->getProperties()->contains(fn(Property $item) => $item->getCode() === $property->getCode());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addProperty(Property $property): self
    {
        $property->setGroup($this);
        $this->properties = $this->getProperties()->add($property);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addPropertyIfNotExists(Property $property): self
    {
        if (!$this->containsProperty($property)) {
            return $this->addProperty($property);
        }

        return $this;
    }

    public function findPropertyReference(Property $property): Property
    {
        $reference = $this->getProperties()->find(fn(Property $item) => $item->getCode() === $property->getCode());

        return $reference ?: $property;
    }
}

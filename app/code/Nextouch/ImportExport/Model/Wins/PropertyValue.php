<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins;

use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyValue
{
    private string $code;
    private ?Property $property;
    private string $description;

    public function __construct(string $code, string $description)
    {
        $this->code = $code;
        $this->description = $description;
    }

    public static function fromArray(array $data): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $code = $propertyAccessor->getValue($data, '[CodiceValore]');
        $description = $propertyAccessor->getValue($data, '[DescrizioneValore]');

        return new self($code, $description);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(Property $property): self
    {
        $this->property = $property;

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
}

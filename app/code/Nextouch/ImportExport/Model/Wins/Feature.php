<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Feature
{
    private string $code;
    private string $description;
    private string $valueCode;
    private string $value;
    private ?FeatureSet $featureSet;

    public function __construct(
        string $code,
        string $description,
        string $valueCode,
        string $value
    ) {
        $this->code = $code;
        $this->description = $description;
        $this->valueCode = $valueCode;
        $this->value = $value;
    }

    public static function fromArray(array $data): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $code = $propertyAccessor->getValue($data, '[CodiceCaratteristica]');
        $description = $propertyAccessor->getValue($data, '[DescrizioneCaratteristica]');
        $valueCode = $propertyAccessor->getValue($data, '[CodiceValore]');
        $value = $propertyAccessor->getValue($data, '[Valore]');

        return new self($code, $description, $valueCode, $value);
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

    public function getValueCode(): string
    {
        return $this->valueCode;
    }

    public function setValueCode(string $valueCode): self
    {
        $this->valueCode = $valueCode;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getFeatureSet(): ?FeatureSet
    {
        return $this->featureSet;
    }

    public function setFeatureSet(FeatureSet $featureSet): self
    {
        $this->featureSet = $featureSet;

        return $this;
    }
}

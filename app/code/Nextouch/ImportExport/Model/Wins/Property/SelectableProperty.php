<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Property;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\ImportExport\Model\Wins\Property;
use Nextouch\ImportExport\Model\Wins\PropertyValue;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SelectableProperty extends Property
{
    public const TYPE = 'Valore';

    private Collection $values;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $code, string $description, int $sortOrder, array $values = [])
    {
        parent::__construct($code, $description, $sortOrder);
        $this->values = new Collection(PropertyValue::class, $values);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): Property
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $code = $propertyAccessor->getValue($data, '[CodiceCaratteristica]');
        $description = $propertyAccessor->getValue($data, '[DescrizioneCaratteristica]');
        $sortOrder = (int) $propertyAccessor->getValue($data, '[Ordinamento]');

        return new self($code, $description, $sortOrder);
    }

    public function getValues(): Collection
    {
        return $this->values;
    }

    public function containsValue(PropertyValue $value): bool
    {
        return $this->getValues()->contains(fn(PropertyValue $item) => $item->getCode() === $value->getCode());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addValue(PropertyValue $value): self
    {
        $value->setProperty($this);
        $this->values = $this->getValues()->add($value);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addValueIfNotExists(PropertyValue $value): self
    {
        if (!$this->containsValue($value)) {
            return $this->addValue($value);
        }

        return $this;
    }

    public function findValueReference(PropertyValue $value): PropertyValue
    {
        $reference = $this->getValues()->find(fn(PropertyValue $item) => $item->getCode() === $value->getCode());

        return $reference ?: $value;
    }
}

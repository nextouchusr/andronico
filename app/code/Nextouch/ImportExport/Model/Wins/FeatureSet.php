<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class FeatureSet
{
    private string $productCode;
    private string $ecatCode;
    private Collection $features;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $productCode, string $ecatCode, array $features = [])
    {
        $this->productCode = $productCode;
        $this->ecatCode = $ecatCode;
        $this->features = new Collection(Feature::class, $features);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $productCode = $propertyAccessor->getValue($data, '[CodiceProdotto]');
        $ecatCode = $propertyAccessor->getValue($data, '[CodiceEcatDM]');

        return new self($productCode, $ecatCode);
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function getEcatCode(): string
    {
        return $this->ecatCode;
    }

    public function getFeatures(): Collection
    {
        return $this->features;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addFeature(Feature $feature): self
    {
        $feature->setFeatureSet($this);
        $this->features = $this->getFeatures()->add($feature);

        return $this;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Collection;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\ImportExport\Model\Wins\FeatureSet as FeatureSetModel;

class FeatureSet implements \IteratorAggregate
{
    private Collection $items;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(array $items = [])
    {
        $this->items = new Collection(FeatureSetModel::class, $items);
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function contains(FeatureSetModel $featureSet): bool
    {
        return $this->getItems()->contains(function (FeatureSetModel $item) use ($featureSet) {
            return $item->getProductCode() === $featureSet->getProductCode();
        });
    }

    /**
     * @throws InvalidArgumentException
     */
    public function add(FeatureSetModel $featureSet): self
    {
        $items = $this->getItems()->add($featureSet);

        return new self($items->toArray());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addIfNotExists(FeatureSetModel $featureSet): self
    {
        if (!$this->contains($featureSet)) {
            return $this->add($featureSet);
        }

        return $this;
    }

    public function findReference(FeatureSetModel $featureSet): FeatureSetModel
    {
        $reference = $this->getItems()->find(function (FeatureSetModel $item) use ($featureSet) {
            return $item->getProductCode() === $featureSet->getProductCode();
        });

        return $reference ?: $featureSet;
    }

    public function getIterator()
    {
        return $this->getItems()->getIterator();
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Collection;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\ImportExport\Model\Wins\Brand as BrandModel;

class Brand implements \IteratorAggregate
{
    private Collection $items;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(array $items = [])
    {
        $this->items = new Collection(BrandModel::class, $items);
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function add(BrandModel $brand): self
    {
        $items = $this->getItems()->add($brand);

        return new self($items->toArray());
    }

    public function getIterator()
    {
        return $this->getItems()->getIterator();
    }
}

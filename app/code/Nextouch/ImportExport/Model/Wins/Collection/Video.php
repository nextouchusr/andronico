<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Collection;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\ImportExport\Model\Wins\Video as VideoModel;

class Video implements \IteratorAggregate
{
    private Collection $items;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(array $items = [])
    {
        $this->items = new Collection(VideoModel::class, $items);
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function add(VideoModel $video): self
    {
        $items = $this->getItems()->add($video);

        return new self($items->toArray());
    }

    public function getIterator()
    {
        return $this->getItems()->getIterator();
    }
}

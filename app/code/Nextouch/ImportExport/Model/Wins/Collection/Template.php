<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Collection;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\ImportExport\Model\Wins\Template as TemplateModel;

class Template implements \IteratorAggregate
{
    private Collection $items;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(array $items = [])
    {
        $this->items = new Collection(TemplateModel::class, $items);
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function contains(TemplateModel $template): bool
    {
        return $this->getItems()->contains(fn(TemplateModel $item) => $item->getCode() === $template->getCode());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function add(TemplateModel $template): self
    {
        $items = $this->getItems()->add($template);

        return new self($items->toArray());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addIfNotExists(TemplateModel $template): self
    {
        if (!$this->contains($template)) {
            return $this->add($template);
        }

        return $this;
    }

    public function findReference(TemplateModel $template): TemplateModel
    {
        $reference = $this->getItems()->find(fn(TemplateModel $item) => $item->getCode() === $template->getCode());

        return $reference ?: $template;
    }

    public function getIterator()
    {
        return $this->getItems()->getIterator();
    }
}

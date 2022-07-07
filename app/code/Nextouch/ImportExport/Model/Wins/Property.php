<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins;

use Collections\Exceptions\InvalidArgumentException;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\ImportExport\Model\Wins\Property\NumericProperty;
use Nextouch\ImportExport\Model\Wins\Property\SelectableProperty;
use Nextouch\ImportExport\Model\Wins\Property\TextualProperty;

abstract class Property
{
    protected string $code;
    protected string $description;
    protected int $sortOrder;
    protected ?Group $group;

    public function __construct(string $code, string $description, int $sortOrder)
    {
        $this->code = $code;
        $this->description = $description;
        $this->sortOrder = $sortOrder;
    }

    /**
     * @throws LocalizedException
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        switch ($data['TipoCaratteristica']) {
            case NumericProperty::TYPE:
                return NumericProperty::fromArray($data);
            case TextualProperty::TYPE:
                return TextualProperty::fromArray($data);
            case SelectableProperty::TYPE:
                return SelectableProperty::fromArray($data);
            default:
                throw new LocalizedException(__('Unknown Wins property type %1.', $data['TipoCaratteristica']));
        }
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

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): self
    {
        $this->group = $group;

        return $this;
    }
}

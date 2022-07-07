<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Brand
{
    public const ONLINE_ACTIVE = 1;
    public const ONLINE_INACTIVE = 2;

    private string $code;
    private string $description;
    private int $online;
    private string $imageUrl;

    public function __construct(string $code, string $description, int $online, string $imageUrl)
    {
        $this->code = $code;
        $this->description = $description;
        $this->online = $online;
        $this->imageUrl = $imageUrl;
    }

    public static function fromArray(array $data): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $code = $propertyAccessor->getValue($data, '[codiceMarchio]');
        $description = $propertyAccessor->getValue($data, '[descrizioneMarchio]');
        $online = (int) $propertyAccessor->getValue($data, '[brandOnline]');
        $imageUrl = $propertyAccessor->getValue($data, '[immagine]');

        return new self($code, $description, $online, $imageUrl);
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

    public function getOnline(): int
    {
        return $this->online;
    }

    public function setOnline(int $online): self
    {
        $this->online = $online;

        return $this;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
}

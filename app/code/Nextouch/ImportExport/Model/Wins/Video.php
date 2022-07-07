<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Video
{
    private string $productCode;
    private string $title;
    private string $url;

    public function __construct(string $productCode, string $title, string $url)
    {
        $this->productCode = $productCode;
        $this->title = $title;
        $this->url = $url;
    }

    public static function fromArray(array $data): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $productCode = $propertyAccessor->getValue($data, '[sku]');
        $title = $propertyAccessor->getValue($data, '[titolo]');
        $url = $propertyAccessor->getValue($data, '[url]');

        return new self($productCode, $title, $url);
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}

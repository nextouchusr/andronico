<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Common;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;

class Callback implements ArrayableInterface
{
    public const USE_TYPE_OK = 'ok';
    public const USE_TYPE_KO = 'ko';
    public const USE_TYPE_CANCEL = 'cancel';
    public const USE_TYPE_ERROR = 'error';
    public const USE_TYPE_LATER = 'later';

    public const ACTION_TYPE_REDIRECT = 'redirect';
    public const ACTION_TYPE_MANUAL = 'manual';

    private string $url;
    private ?string $use;
    private ?string $action;
    private ?string $label;
    private ?string $description;

    public function __construct(
        string $url,
        ?string $use = null,
        ?string $action = null,
        ?string $label = null,
        ?string $description = null
    ) {
        $this->url = $url;
        $this->use = $use;
        $this->action = $action;
        $this->label = $label;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getUse(): ?string
    {
        return $this->use;
    }

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['url']) ? (string) $data['url'] : null,
            isset($data['use']) ? (string) $data['use'] : null,
            isset($data['action']) ? (string) $data['action'] : null,
            isset($data['label']) ? (string) $data['label'] : null,
            isset($data['description']) ? (string) $data['description'] : null
        );
    }

    public function toArray(): array
    {
        $arr = [
            'url' => $this->getUrl(),
            'use' => $this->getUse(),
            'action' => $this->getAction(),
            'label' => $this->getLabel(),
            'description' => $this->getDescription(),
        ];

        return array_filter($arr);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Plan;

use Magento\Framework\App\ObjectManager;
use Nextouch\Findomestic\Api\Data\ArrayableInterface;
use Nextouch\Findomestic\Helper\FindomesticConfig;

class Program implements ArrayableInterface
{
    private string $programId;
    private ?int $duration;

    public function __construct(string $programId, ?int $duration = null)
    {
        $this->programId = $programId;
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getProgramId(): string
    {
        return $this->programId;
    }

    /**
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public static function asDefault(): self
    {
        $findomesticConfig = ObjectManager::getInstance()->get(FindomesticConfig::class);

        return new self($findomesticConfig->getProgramId());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['programId'],
            isset($data['duration']) ? (int) $data['duration'] : null
        );
    }

    public function toArray(): array
    {
        $arr = [
            'programId' => $this->getProgramId(),
            'duration' => $this->getDuration(),
        ];

        return array_filter($arr);
    }
}

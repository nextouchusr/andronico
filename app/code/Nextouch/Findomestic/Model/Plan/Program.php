<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Plan;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;

class Program implements ArrayableInterface
{
    private const DEFAULT_PROGRAM_ID = '7593';

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
        return new self(self::DEFAULT_PROGRAM_ID);
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

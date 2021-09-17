<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Cron;

use Nextouch\ImportExport\Api\EntityDataOperationInterface;

class EntityDataOperationsCombiner
{
    /** @var EntityDataOperationInterface[] */
    private array $operations;

    public function __construct(array $operations)
    {
        $this->operations = $operations;
    }

    public function execute(): void
    {
        \Lambdish\Phunctional\each(fn(EntityDataOperationInterface $item) => $item->run(), $this->operations);
    }
}

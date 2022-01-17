<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Directory;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Directory\Status;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class GetStatusList implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $statuses;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(StatusReturn $statusReturn, array $statuses = [])
    {
        $this->statusReturn = $statusReturn;
        $this->statuses = new Collection(Status::class, $statuses);
    }

    /**
     * @return \Nextouch\FastEst\Model\Common\StatusReturn
     */
    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    /**
     * @return \Nextouch\FastEst\Model\Directory\Status[]
     */
    public function getStatuses(): array
    {
        return $this->statuses->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $statuses = $propertyAccessor->getValue($object, 'status_ids');
        $statuses = is_array($statuses) ? $statuses : [$statuses];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => Status::fromObject($item), $statuses)
        );
    }
}

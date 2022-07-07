<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Directory;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Directory\Hour;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class GetHourList implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $hours;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(StatusReturn $statusReturn, array $hours = [])
    {
        $this->statusReturn = $statusReturn;
        $this->hours = new Collection(Hour::class, $hours);
    }

    /**
     * @return \Nextouch\FastEst\Model\Common\StatusReturn
     */
    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    /**
     * @return \Nextouch\FastEst\Model\Directory\Hour[]
     */
    public function getHours(): array
    {
        return $this->hours->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $hours = $propertyAccessor->getValue($object, 'hour_ids');
        $hours = is_array($hours) ? $hours : [$hours];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => Hour::fromObject($item), $hours)
        );
    }
}

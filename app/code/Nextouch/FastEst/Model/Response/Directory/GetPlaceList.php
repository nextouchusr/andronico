<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Directory;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Directory\Place;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class GetPlaceList implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $places;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(StatusReturn $statusReturn, array $places = [])
    {
        $this->statusReturn = $statusReturn;
        $this->places = new Collection(Place::class, $places);
    }

    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public function getPlaces(): Collection
    {
        return $this->places;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $places = $propertyAccessor->getValue($object, 'place_ids');
        $places = is_array($places) ? $places : [$places];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => Place::fromObject($item), $places)
        );
    }
}

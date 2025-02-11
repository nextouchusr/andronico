<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Directory;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Directory\Headquarter;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class GetHeadquarterList implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $headquarters;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(StatusReturn $statusReturn, array $headquarters = [])
    {
        $this->statusReturn = $statusReturn;
        $this->headquarters = new Collection(Headquarter::class, $headquarters);
    }

    /**
     * @return \Nextouch\FastEst\Model\Common\StatusReturn
     */
    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    /**
     * @return \Nextouch\FastEst\Model\Directory\Headquarter[]
     */
    public function getHeadquarters(): array
    {
        return $this->headquarters->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $headquarters = $propertyAccessor->getValue($object, 'headquarter_ids');
        $headquarters = is_array($headquarters) ? $headquarters : [$headquarters];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => Headquarter::fromObject($item), $headquarters)
        );
    }
}

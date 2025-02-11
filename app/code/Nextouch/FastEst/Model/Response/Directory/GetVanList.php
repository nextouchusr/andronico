<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Directory;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Directory\Van;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class GetVanList implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $vans;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(StatusReturn $statusReturn, array $vans = [])
    {
        $this->statusReturn = $statusReturn;
        $this->vans = new Collection(Van::class, $vans);
    }

    /**
     * @return \Nextouch\FastEst\Model\Common\StatusReturn
     */
    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    /**
     * @return \Nextouch\FastEst\Model\Directory\Van[]
     */
    public function getVans(): array
    {
        return $this->vans->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $vans = $propertyAccessor->getValue($object, 'van_ids');
        $vans = is_array($vans) ? $vans : [$vans];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => Van::fromObject($item), $vans)
        );
    }
}

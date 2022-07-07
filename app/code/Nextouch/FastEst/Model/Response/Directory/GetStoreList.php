<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Directory;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Directory\Store;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class GetStoreList implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $stores;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(StatusReturn $statusReturn, array $stores = [])
    {
        $this->statusReturn = $statusReturn;
        $this->stores = new Collection(Store::class, $stores);
    }

    /**
     * @return \Nextouch\FastEst\Model\Common\StatusReturn
     */
    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    /**
     * @return \Nextouch\FastEst\Model\Directory\Store[]
     */
    public function getStores(): array
    {
        return $this->stores->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $stores = $propertyAccessor->getValue($object, 'store_ids');
        $stores = is_array($stores) ? $stores : [$stores];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => Store::fromObject($item), $stores)
        );
    }
}

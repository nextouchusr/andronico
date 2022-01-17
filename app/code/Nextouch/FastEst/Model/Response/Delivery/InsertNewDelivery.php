<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Delivery;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Delivery\DeliveryReturn;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class InsertNewDelivery implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $deliveryReturns;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(StatusReturn $statusReturn, array $deliveryReturns = [])
    {
        $this->statusReturn = $statusReturn;
        $this->deliveryReturns = new Collection(DeliveryReturn::class, $deliveryReturns);
    }

    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public function getDeliveryReturns(): array
    {
        return $this->deliveryReturns->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $deliveryReturns = $propertyAccessor->getValue($object, 'delivery_return');
        $deliveryReturns = is_array($deliveryReturns) ? $deliveryReturns : [$deliveryReturns];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => DeliveryReturn::fromObject($item), $deliveryReturns)
        );
    }
}

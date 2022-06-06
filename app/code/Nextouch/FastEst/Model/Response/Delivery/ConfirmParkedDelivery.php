<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Delivery;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Delivery\DeliveryReturn;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ConfirmParkedDelivery implements OutputInterface
{
    private StatusReturn $statusReturn;
    private DeliveryReturn $deliveryReturn;

    private function __construct(StatusReturn $statusReturn, DeliveryReturn $deliveryReturn)
    {
        $this->statusReturn = $statusReturn;
        $this->deliveryReturn = $deliveryReturn;
    }

    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public function getDeliveryReturn(): DeliveryReturn
    {
        return $this->deliveryReturn;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $deliveryReturn = $propertyAccessor->getValue($object, 'delivery_return');

        return new self(
            StatusReturn::fromObject($statusReturn),
            DeliveryReturn::fromObject($deliveryReturn)
        );
    }
}

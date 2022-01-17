<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Appointment;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Appointment\SlotResponse;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class GetAvailableSlotList implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $slotResponse;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(StatusReturn $statusReturn, array $slotResponse = [])
    {
        $this->statusReturn = $statusReturn;
        $this->slotResponse = new Collection(SlotResponse::class, $slotResponse);
    }

    /**
     * @return \Nextouch\FastEst\Model\Common\StatusReturn
     */
    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    /**
     * @return \Nextouch\FastEst\Model\Appointment\SlotResponse[]
     */
    public function getSlotResponse(): array
    {
        return $this->slotResponse->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $slotResponse = $propertyAccessor->getValue($object, 'slot_response');
        $slotResponse = is_array($slotResponse) ? $slotResponse : [$slotResponse];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => SlotResponse::fromObject($item), $slotResponse)
        );
    }
}

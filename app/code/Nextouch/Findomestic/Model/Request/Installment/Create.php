<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Request\Installment;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\Findomestic\Api\Data\InputInterface;
use Nextouch\Findomestic\Model\Common\Callback;
use Nextouch\Findomestic\Model\Order\Details as OrderDetails;
use Nextouch\Quote\Api\Data\CartInterface;
use function Lambdish\Phunctional\map;

class Create implements InputInterface
{
    private string $sessionExpiry;
    private OrderDetails $orderDetails;
    private Collection $callbacks;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $sessionExpiry,
        OrderDetails $orderDetails,
        array $callbacks = []
    ) {
        $this->sessionExpiry = $sessionExpiry;
        $this->orderDetails = $orderDetails;
        $this->callbacks = new Collection(Callback::class, $callbacks);
    }

    /**
     * @return string
     */
    public function getSessionExpiry(): string
    {
        return $this->sessionExpiry;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Order\Details
     */
    public function getOrderDetails(): OrderDetails
    {
        return $this->orderDetails;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Common\Callback[]
     */
    public function getCallbacks(): array
    {
        return $this->callbacks->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromDomain(CartInterface $quote, array $callbacks = []): self
    {
        $callbacks = map(fn(array $item) => Callback::fromArray($item), $callbacks);

        return new self(
            gmdate('Y-m-d\TH:i:s.\0\0\0\Z', strtotime('+3 hour')),
            OrderDetails::fromDomain($quote),
            $callbacks
        );
    }

    public function toArray(): array
    {
        return [
            'sessionExpiry' => $this->getSessionExpiry(),
            'orderDetail' => $this->getOrderDetails()->toArray(),
            'callbacks' => $this->callbacks->map(fn(Callback $item) => $item->toArray())->toArray(),
        ];
    }
}

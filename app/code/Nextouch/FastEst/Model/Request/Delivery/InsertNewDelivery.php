<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Request\Delivery;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Delivery\Customer;
use Nextouch\FastEst\Model\Delivery\DeliveryBase;
use Nextouch\FastEst\Model\Delivery\Product;

class InsertNewDelivery implements InputInterface
{
    private Login $login;
    private Customer $customer;
    private DeliveryBase $deliveryBase;
    private Collection $products;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        Login $login,
        Customer $customer,
        DeliveryBase $deliveryBase,
        array $products = []
    ) {
        $this->login = $login;
        $this->customer = $customer;
        $this->deliveryBase = $deliveryBase;
        $this->products = new Collection(Product::class, $products);
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getDeliveryBase(): DeliveryBase
    {
        return $this->deliveryBase;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->login = $this->getLogin()->asObject();
        $object->customer = $this->getCustomer()->asObject();
        $object->delivery_base = $this->getDeliveryBase()->asObject();
        $object->products = $this->getProducts()->map(fn(Product $item) => $item->asObject())->toArray();

        return $object;
    }
}

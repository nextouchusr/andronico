<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Nextouch\FastEst\Api\DeliveryRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Delivery\Customer;
use Nextouch\FastEst\Model\Delivery\DeliveryBase;
use Nextouch\FastEst\Model\Delivery\Product;
use Nextouch\FastEst\Model\Request\Delivery\InsertNewDelivery as InsertNewDeliveryRequest;
use Nextouch\FastEst\Model\Response\Delivery\InsertNewDelivery as InsertNewDeliveryResponse;
use Nextouch\Sales\Api\Data\ShipmentInterface;
use Nextouch\Sales\Api\Data\ShipmentItemInterface;
use function Lambdish\Phunctional\map;

class DeliveryRepository extends AbstractBaseRepository implements DeliveryRepositoryInterface
{
    public function create(ShipmentInterface $shipment): InsertNewDeliveryResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $customer = Customer::fromDomain($shipment->getShippingAddress());
        $deliveryBase = DeliveryBase::fromDomain(); // TODO: pass domain object
        $products = map(fn(ShipmentItemInterface $item) => Product::fromDomain($item), $shipment->getItems());
        $request = new InsertNewDeliveryRequest($login, $customer, $deliveryBase, $products);

        $result = $this->client->call('insert_new_delivery', $request->asObject());

        return InsertNewDeliveryResponse::fromObject($result);
    }
}

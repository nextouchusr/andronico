<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Nextouch\FastEst\Api\DeliveryRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Delivery\Customer;
use Nextouch\FastEst\Model\Delivery\DeliveryBase;
use Nextouch\FastEst\Model\Delivery\Product;
use Nextouch\FastEst\Model\Request\Delivery\ConfirmParkedDelivery as ConfirmParkedDeliveryRequest;
use Nextouch\FastEst\Model\Request\Delivery\GetOrderLabels as GetOrderLabelsRequest;
use Nextouch\FastEst\Model\Request\Delivery\InsertNewDelivery as InsertNewDeliveryRequest;
use Nextouch\FastEst\Model\Response\Delivery\ConfirmParkedDelivery as ConfirmParkedDeliveryResponse;
use Nextouch\FastEst\Model\Response\Delivery\GetOrderLabels as GetOrderLabelsResponse;
use Nextouch\FastEst\Model\Response\Delivery\InsertNewDelivery as InsertNewDeliveryResponse;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\Data\OrderItemInterface;
use function Lambdish\Phunctional\map;

class DeliveryRepository extends AbstractBaseRepository implements DeliveryRepositoryInterface
{
    public function create(OrderInterface $order): InsertNewDeliveryResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $customer = Customer::fromDomain($order->getShippingAddress());
        $deliveryBase = DeliveryBase::fromDomain($order);
        $products = map(fn(OrderItemInterface $item) => Product::fromDomain($item), $order->getItems());
        $request = new InsertNewDeliveryRequest($login, $customer, $deliveryBase, $products);

        $result = $this->doRequest('insert_new_delivery', $request);

        return InsertNewDeliveryResponse::fromObject($result);
    }

    public function confirmParked(OrderInterface $order): ConfirmParkedDeliveryResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $storeOrder = $order->getIncrementId();
        $request = new ConfirmParkedDeliveryRequest($login, $storeOrder);

        $result = $this->doRequest('confirm_parked_delivery', $request);

        return ConfirmParkedDeliveryResponse::fromObject($result);
    }

    public function getOrderLabels(OrderInterface $order): GetOrderLabelsResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $storeOrder = $order->getIncrementId();
        $request = new GetOrderLabelsRequest($login, $storeOrder);

        $result = $this->doRequest('get_labels_by_storeorder', $request);

        return GetOrderLabelsResponse::fromObject($result);
    }
}

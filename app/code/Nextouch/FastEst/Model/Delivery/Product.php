<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Delivery;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\Sales\Api\Data\ShipmentItemInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Assert\Assert;

class Product implements InputInterface, OutputInterface
{
    private int $productId;
    private int $quantity;
    private string $brand;
    private string $model;
    private int $placeId;
    private int $usedProductId;
    private ServiceList $serviceList;

    private function __construct(
        int $productId,
        int $quantity,
        string $brand,
        string $model,
        int $placeId,
        int $usedProductId,
        ServiceList $serviceList
    ) {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->brand = $brand;
        $this->model = $model;
        $this->placeId = $placeId;
        $this->usedProductId = $usedProductId;
        $this->serviceList = $serviceList;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getPlaceId(): int
    {
        return $this->placeId;
    }

    public function getUsedProductId(): int
    {
        return $this->usedProductId;
    }

    public function getServiceList(): ServiceList
    {
        return $this->serviceList;
    }

    public static function fromDomain(ShipmentItemInterface $shipmentItem): self
    {
        $orderItem = $shipmentItem->getOrderItem();
        Assert::notNull($orderItem);

        $product = $orderItem->getProduct();
        Assert::notNull($product);

        return new self(
            $product->getFastEstType(),
            (int) $shipmentItem->getQty(),
            $product->getBrandDescription(),
            $product->getName(),
            10, // TODO: replace mock data
            $product->getFastEstType(),
            ServiceList::fromDomain($orderItem)
        );
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $productId = (int) $propertyAccessor->getValue($object, 'product_id');
        $quantity = (int) $propertyAccessor->getValue($object, 'quantity');
        $brand = (string) $propertyAccessor->getValue($object, 'product_brand');
        $model = (string) $propertyAccessor->getValue($object, 'product_model');
        $placeId = (int) $propertyAccessor->getValue($object, 'product_place_id');
        $usedProductId = (int) $propertyAccessor->getValue($object, 'used_product_id');
        $services = $propertyAccessor->getValue($object, 'services');

        return new self(
            $productId,
            $quantity,
            $brand,
            $model,
            $placeId,
            $usedProductId,
            ServiceList::fromObject($services)
        );
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->product_id = $this->getProductId();
        $object->quantity = $this->getQuantity();
        $object->product_brand = $this->getBrand();
        $object->product_model = $this->getModel();
        $object->product_place_id = $this->getPlaceId();
        $object->used_product_id = $this->getUsedProductId();
        $object->services = $this->getServiceList()->asObject();

        return $object;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Delivery;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DeliveryReturn implements OutputInterface
{
    private int $deliveryId;
    private int $errorCode;
    private string $errorDescription;
    private string $storeOrder;
    private int $changeId;
    private ?Product $product;

    private function __construct(
        int $deliveryId,
        int $errorCode,
        string $errorDescription,
        string $storeOrder,
        int $changeId,
        ?Product $product = null
    ) {
        $this->deliveryId = $deliveryId;
        $this->errorCode = $errorCode;
        $this->errorDescription = $errorDescription;
        $this->storeOrder = $storeOrder;
        $this->changeId = $changeId;
        $this->product = $product;
    }

    public function getDeliveryId(): int
    {
        return $this->deliveryId;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getErrorDescription(): string
    {
        return $this->errorDescription;
    }

    public function getStoreOrder(): string
    {
        return $this->storeOrder;
    }

    public function getChangeId(): int
    {
        return $this->changeId;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $deliveryId = (int) $propertyAccessor->getValue($object, 'delivery_id');
        $errorCode = (int) $propertyAccessor->getValue($object, 'error_code');
        $errorDescription = (string) $propertyAccessor->getValue($object, 'error_descr');
        $storeOrder = (string) $propertyAccessor->getValue($object, 'store_order');
        $changeId = (int) $propertyAccessor->getValue($object, 'change_id');
        $hasProduct = $propertyAccessor->isReadable($object, 'product');

        if ($hasProduct) {
            $product = $propertyAccessor->getValue($object, 'product');

            return new self(
                $deliveryId,
                $errorCode,
                $errorDescription,
                $storeOrder,
                $changeId,
                Product::fromObject($product)
            );
        }


        return new self(
            $deliveryId,
            $errorCode,
            $errorDescription,
            $storeOrder,
            $changeId
        );
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Directory;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Directory\Product;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class ProductListResponse implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $products;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(StatusReturn $statusReturn, array $products = [])
    {
        $this->statusReturn = $statusReturn;
        $this->products = new Collection(Product::class, $products);
    }

    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $products = $propertyAccessor->getValue($object, 'product_ids');
        $products = is_array($products) ? $products : [$products];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => Product::fromObject($item), $products)
        );
    }
}

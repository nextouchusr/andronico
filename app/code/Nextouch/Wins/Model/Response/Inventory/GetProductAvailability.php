<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Response\Inventory;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\Wins\Api\Data\OutputInterface;
use Nextouch\Wins\Model\Common\StatusReturn;
use Nextouch\Wins\Model\Inventory\ProductStock;
use function Lambdish\Phunctional\map;

class GetProductAvailability implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $productList;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(StatusReturn $statusReturn, array $productList = [])
    {
        $this->statusReturn = $statusReturn;
        $this->productList = new Collection(ProductStock::class, $productList);
    }

    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public function getProductList(): array
    {
        return $this->productList->toArray();
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            StatusReturn::success(),
            map(fn(array $data) => ProductStock::fromArray($data), (array) $data['ProductList'])
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromError(array $data): self
    {
        return new self(StatusReturn::error($data));
    }
}

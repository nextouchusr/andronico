<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Directory;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Product implements OutputInterface
{
    private int $productId;
    private string $description;

    private function __construct(int $productId, string $description)
    {
        $this->productId = $productId;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $productId = (int) $propertyAccessor->getValue($object, 'product_id');
        $description = (string) $propertyAccessor->getValue($object, 'descr');

        return new self($productId, $description);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Plugin\Model\Product\Type;

use Magento\Catalog\Model\Product\Type\AbstractType;
use function Lambdish\Phunctional\first;

class RemoveSkuAppendedByCustomOptions
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetOptionSku(AbstractType $subject, string $result): string
    {
        preg_match('/[^-]*-[^-]*/', $result, $matches);

        return (string) first($matches);
    }
}

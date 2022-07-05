<?php
declare(strict_types=1);

namespace Nextouch\Quote\Api\Data;

use Nextouch\Catalog\Api\Data\ProductInterface;

/**
 * @api
 */
interface CartItemInterface extends \Magento\Quote\Api\Data\CartItemInterface
{
    public const OPTION_SEPARATOR = ',';

    /**
     * @return \Nextouch\Catalog\Api\Data\ProductInterface
     */
    public function getProduct(): ProductInterface;

    /**
     * @return bool
     */
    public function hasSelectedService(string $code): bool;
}

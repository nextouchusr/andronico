<?php
declare(strict_types=1);

namespace Nextouch\Quote\Api\Data;

use Nextouch\Catalog\Api\Data\ProductInterface;

/**
 * @api
 */
interface CartItemInterface extends \Magento\Quote\Api\Data\CartItemInterface
{
    /**
     * @return \Nextouch\Catalog\Api\Data\ProductInterface
     */
    public function getProduct(): ProductInterface;
}

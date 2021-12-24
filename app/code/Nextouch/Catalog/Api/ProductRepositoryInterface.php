<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api;

use Nextouch\Catalog\Api\Data\ProductInterface;

/**
 * @api
 */
interface ProductRepositoryInterface
{
    /**
     * @param int $productId
     * @param bool $editMode
     * @param int|null $storeId
     * @return \Nextouch\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $productId, bool $editMode = false, int $storeId = null): ProductInterface;
}

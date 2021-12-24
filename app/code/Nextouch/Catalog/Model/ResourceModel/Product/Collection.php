<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model\ResourceModel\Product;

use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Nextouch\Catalog\Model\Product;

class Collection extends ProductCollection
{
    protected function _construct(): void
    {
        $this->_init(Product::class, ProductResourceModel::class);
    }
}

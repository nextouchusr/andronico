<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model\ResourceModel\Category;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\ResourceModel\Category as CategoryResourceModel;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Catalog\Model\Category;

class Collection extends CategoryCollection
{
    protected function _construct(): void
    {
        $this->_init(Category::class, CategoryResourceModel::class);
    }

    /**
     * @throws LocalizedException
     */
    public function addNameToFilter(string $name): self
    {
        $this->addAttributeToFilter(CategoryInterface::KEY_NAME, $name);

        return $this;
    }
}

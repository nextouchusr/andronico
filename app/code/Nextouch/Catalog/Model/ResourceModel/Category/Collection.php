<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model\ResourceModel\Category;

use Magento\Catalog\Model\ResourceModel\Category as CategoryResourceModel;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Catalog\Api\Data\CategoryInterface;
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
    public function addUrlPathToFilter(string $urlPath): self
    {
        $this->addAttributeToFilter(CategoryInterface::URL_PATH, $urlPath);

        return $this;
    }
}

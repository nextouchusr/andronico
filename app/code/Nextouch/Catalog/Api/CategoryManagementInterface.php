<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api;

/**
 * @api
 */
interface CategoryManagementInterface
{
    /**
     * @return \Magento\Catalog\Api\Data\CategoryTreeInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMenuCategoriesSearch(): array;
}

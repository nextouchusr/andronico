<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Nextouch\Catalog\Api\CategoryManagementInterface;

class CategoryManagement implements CategoryManagementInterface
{
    private \Magento\Catalog\Api\CategoryManagementInterface $categoryManagement;

    public function __construct(\Magento\Catalog\Api\CategoryManagementInterface $categoryManagement)
    {
        $this->categoryManagement = $categoryManagement;
    }

    public function getMenuCategoriesSearch(): array
    {
        $categoryTree = $this->categoryManagement->getTree(null, 1);

        return $categoryTree->getChildrenData();
    }
}

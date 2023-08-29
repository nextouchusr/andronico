<?php

namespace Elightwalk\CatalogSearch\Plugin\Helper\Product;

class ProductList
{

    protected $request;

    public function __construct(
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->request = $request;
    }

    public function afterGetDefaultSortField(\Magento\Catalog\Helper\Product\ProductList $subject, $result)
    {

        $moduleName = $this->request->getModuleName();
        $controller = $this->request->getControllerName();
        $action = $this->request->getActionName();
        $route = $this->request->getRouteName();

        if ($moduleName == 'catalogsearch' && $controller == 'result' && $action == 'index') {
            return 'relevance';
        }

        return $result;
    }
}
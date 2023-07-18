<?php

namespace Elightwalk\Core\Plugin\Wishlist\Controller\Index;

use Magento\Framework\App\Action\Action;

class Add
{
    public function aroundExecute(Action $controller, callable $proceed)
    {
        return $proceed();
        //$resultRedirect->setUrl($this->_redirect->getRefererUrl());
        //return $resultRedirect;
    }
}

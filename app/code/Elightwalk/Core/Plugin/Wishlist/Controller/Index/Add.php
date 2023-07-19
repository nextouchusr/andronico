<?php

namespace Elightwalk\Core\Plugin\Wishlist\Controller\Index;

use Magento\Wishlist\Controller\Index\Add as MagentoAdd;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;

class Add
{
    /**
     * @var RedirectInterface
     */
    private $_redirectIntrface;

    /**
     * __construct
     *
     * @param RedirectInterface $redirectIntrface
     */
    public function __construct(
        RedirectInterface $redirectIntrface
    ) {
        $this->_redirectIntrface = $redirectIntrface;
    }

    /**
     * AfterExecute
     *
     * @param MagentoAdd $subject
     * @param Redirect $resultRedirect
     * @return Redirect
     */
    public function afterExecute(MagentoAdd $subject, Redirect $resultRedirect)
    {
        $resultRedirect->setUrl($this->_redirectIntrface->getRefererUrl());

        return $resultRedirect;
    }
}

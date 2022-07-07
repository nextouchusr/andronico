<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Observer;

use Magento\Customer\Model\Visitor;
use Magento\Customer\Observer\Visitor\AbstractVisitorObserver;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Filesystem\DirectoryList;

class TestObserver extends AbstractVisitorObserver
{
    protected static $registered = false;
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @param Observer $observer
     * @return void
     */

    public function __construct(Visitor $visitor, DirectoryList $directoryList)
    {
        parent::__construct($visitor);
        $this->visitor = $visitor;
        $this->directoryList = $directoryList;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        //echo 'test observer';
    }
}

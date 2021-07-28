<?php

/**
 * Copyright © Axepta S.p.a. All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Controller\Payment;

use Axepta\Paymentservice\Controller\Payment;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;

class Callback extends Payment implements CsrfAwareActionInterface
{
    protected $request;

    public function execute()
    {
        /** @var \Magento\Framework\UrlInterface $urlInterface */

        $helper = $this->_objectManager->get('\Axepta\Paymentservice\Helper\Data');
        $postData = $this->request->getPost();

        if ($helper->getMethod() == 'computop') {
            $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
            $restURLParams = explode('/', $urlInterface->getCurrentUrl());
            $restURLParams[5] = 'notify';
            $notifyURL = implode('/', $restURLParams);
            header('Location:' . $notifyURL);
        }
        /*
        if ($helper->getMethod() == 'axepta') {
            // perform key values fields
            // file_put_contents('servercallback.txt',file_get_contents('php://input')); //debug only

            //$postData = $this->request->getPost();
            // $postData = $callbackData;

            //file_put_contents('postcall.txt',serialize($postData)); //debug only
        }*/

        $this->callbackAction($postData);
    }

    private function objectToArray($obj)
    {
        //only process if it's an object or array being passed to the function
        if (is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;
            foreach ($ret as &$item) {
                //recursively process each element regardless of type
                $item = $this->objectToArray($item);
            }
            return $ret;
        }
        //otherwise (i.e. for scalar values) return without modification
        return $obj;
    }

    public function createCsrfValidationException(RequestInterface $request): ? InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
         $this->request = $request;
         return true;
    }
}

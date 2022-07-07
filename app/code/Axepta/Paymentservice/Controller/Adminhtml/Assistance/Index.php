<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Controller\Adminhtml\Assistance;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\View\Result\PageFactory;
use Magento\Setup\Exception;

class Index extends Action
{
    const SENDER_EMAIL = 'trans_email/ident_general/email';

    const XML_PATH_EMAIL_RECIPIENT = 'contact/email/recipient_email';

    const XML_PATH_EMAIL_TEMPLATE = 'bnl_email_template';

    /**
     * @var Context
     */
    private $context;
    /**
     * @var PageFactory
     */
    private $pageFactory;

    private $scopeConfig;

    private $inlineTranslation;
    private $storeManager;
    private $escaper;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        parent::__construct($context);
        $this->scopeConfig = $this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        $this->context = $context;
        $this->pageFactory = $pageFactory;
        $this->inlineTranslation = $this->_objectManager->get('\Magento\Framework\Translate\Inline\StateInterface');
        $this->escaper = $this->_objectManager->get('\Magento\Framework\Escaper');
        $this->storeManager = $this->_objectManager->get('\Magento\Store\Model\StoreManagerInterface');
    }


    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws Exception
     */
    public function execute()
    {
        $resultRedirect =  $this->pageFactory->create();

        $post = $this->getRequest()->getPost();

        $this->inlineTranslation->suspend();

        if (isset($post) && count($post)) {
            $post = (array)$post;
            $post['cc'] = (array)$post['cc'];
            array_push($post['cc'], 'ecommercesupport@axepta.it');
            $postObject = new \Magento\Framework\DataObject();
            $postObject = $postObject->setData($post);

            $helper = $this->_objectManager->get('\Axepta\Paymentservice\Helper\Data');
            $scopeConfig = $this->_objectManager->get('\Magento\Framework\App\Config');
            $storeManager = $this->_objectManager->get('\Magento\Store\Model\StoreManager');

            $transportBuilder = $this->_objectManager->create('\Magento\Framework\Mail\Template\TransportBuilder');

            $cc = $this->getRequest()->getParam('cc', null);
            $message = $this->getRequest()->getParam('message', null);


            $payment_instruments = explode(',', $helper->getConfigByCurrentMethod('payment_instruments'));
            $payment_instruments = array_filter($payment_instruments);

            $message .= sprintf('<br><br><h3>Configuration</h3><br>');
            $message .= sprintf('<strong>Method</strong>: %s<br>', $helper->getConfig('payment_method'));
            $message .= sprintf('<strong>Checkout type</strong>: %s<br>', $helper->getConfig('checkout_type'));
            $message .= sprintf('<strong>Acquirer</strong>: %s<br>', $helper->getConfig('acquirer'));
            $message .= sprintf('<strong>Payment instruments</strong>: %s<br>', implode(', ', $payment_instruments));
            $message .= sprintf('<strong>trtype</strong>: %s<br>', $helper->getConfigByCurrentMethod('tr_type'));
            $message .= sprintf('<strong>Tid</strong>: %s<br>', $helper->getConfigByCurrentMethod('tid'));
            $message .= sprintf('<strong>Tid Findomestic</strong>: %s<br>', $helper->getConfig('tid_findomestic'));
            $message .= sprintf('<strong>Ksig</strong>: %s<br>', $helper->getConfigByCurrentMethod('ksig'));
            $message .= sprintf('<strong>hMacPassword</strong>: %s<br>', $helper->getConfig('hmac_password'));
            $message .= sprintf('<strong>Token enabled</strong>: %s<br>', $helper->getConfig('enable_token_generation'));
            $message .= sprintf('<strong>One click buy enabled</strong>: %s<br>', $helper->getConfig('enable_one_click_buy'));
            $message .= sprintf('<strong>Sandbox</strong>: %s<br>', $helper->getConfig('debug'));
            $message .= sprintf('<strong>Currency</strong>: %s<br>', $this->storeManager->getStore()->getCurrentCurrencyCode());
            $message .= sprintf('<strong>Language</strong>: %s<br>', $this->storeManager->getStore()->getLocaleCode());

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

            $sender ['email'] = $this->scopeConfig->getValue(self::SENDER_EMAIL);
            $sender['name'] = 'admin';
            $resultRedirect =  $this->pageFactory->create();

            try {
                $transport = $transportBuilder
                    ->setTemplateIdentifier(self::XML_PATH_EMAIL_TEMPLATE)
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                            'message' => $message
                        ]
                    )
                    ->setTemplateVars(
                        [
                            'data' => $postObject,
                            'message' => $message
                        ]
                    )
                    ->setFrom($sender)
                    ->addTo($post['cc'], $storeScope)
                    ->getTransport();

                $transport->sendMessage();
                $this->inlineTranslation->resume();
                $this->messageManager->addSuccess(__('Email has been sent successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Something went wrong. Please try again later.'));
                throw new Exception($e->getMessage());
            }
        }
        return $resultRedirect;
    }
}

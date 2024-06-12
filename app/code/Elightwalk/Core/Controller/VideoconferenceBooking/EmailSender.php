<?php

namespace Elightwalk\Core\Controller\VideoconferenceBooking;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator;
use Psr\Log\LoggerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Message\Manager;
use Magento\Framework\App\Area;
use Magento\Store\Model\Store;
use Magento\Store\Model\ScopeInterface;

class EmailSender extends Action
{
    public const XML_PATH_EMAIL_RECIPIENT_NAME  = 'trans_email/ident_general/name';
    public const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_general/email';
    public const EMAIL_RECIPIENT                = "aps@nextouch.it ";

    /**
     * @var Validator
     */
    protected $_formKeyValidator;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var StateInterface
     */
    protected $_inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Manager
     */
    protected $_messageManager;

    /**
     * __construct
     *
     * @param Context $context
     * @param Validator $formKeyValidator
     * @param LoggerInterface $logger
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        Validator $formKeyValidator,
        LoggerInterface $logger,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->_formKeyValidator  = $formKeyValidator;
        $this->_logger            = $logger;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder  = $transportBuilder;
        $this->_scopeConfig       = $scopeConfig;
        $this->_messageManager    = $context->getMessageManager();
    }

    /**
     * Execute
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        if ($this->_formKeyValidator->validate($this->getRequest())) {
            $post = $this->getRequest()->getPost();

            try {
                $this->_inlineTranslation->suspend();

                $sentToEmail = $this->_scopeConfig->getValue('trans_email/ident_general/email', ScopeInterface::SCOPE_STORE);
                $sentToName  = $this->_scopeConfig->getValue('trans_email/ident_general/name', ScopeInterface::SCOPE_STORE);
                $recipient   = self::EMAIL_RECIPIENT;

                $sender = [
                    'email' => $sentToEmail,
                    'name'  => $sentToName
                ];

                $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('elightwalk_core_videoconferencebooking_emailtemplate')
                    ->setTemplateOptions([
                        'area'  => Area::AREA_FRONTEND,
                        'store' => Store::DEFAULT_STORE_ID,
                    ])
                    ->setTemplateVars([
                        'name'     => $post['name'],
                        'surname'  => $post['surname'],
                        'email'    => $post['email'],
                        'phone'    => $post['phone'],
                        'category' => $post['category'],
                        'date'     => $post['date'],
                        'time'     => $post['time'],
                        'url'      => isset($post['url']) ? $post['url'] : "",
                    ])
                    ->setFrom($sender)
                    ->addTo($recipient, $sentToName)
                    ->addCc($post['email'], $sentToName)
                    ->getTransport();

                $transport->sendMessage();
                $this->_inlineTranslation->resume();

                $successMessage = __('Thank for your email. We will get back to you soon.');
                $this->messageManager->addSuccess($successMessage);

                $this->_redirect($this->_redirect->getRefererUrl());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_logger->debug($e->getMessage());
            }
        } else {
            $this->messageManager->addError("Invalid Form Key. Please refresh the page");
        }

        $this->_redirect($this->_redirect->getRefererUrl());
    }
}

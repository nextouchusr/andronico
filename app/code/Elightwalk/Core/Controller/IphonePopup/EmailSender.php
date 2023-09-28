<?php

namespace Elightwalk\Core\Controller\IphonePopup;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator;
use Psr\Log\LoggerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Message\Manager;
use Magento\Framework\App\Area;
use Magento\Store\Model\Store;

class EmailSender extends Action
{
    public const SEND_TO_EMAIL = "info@andronico.it"; //"maurizio.andronico@nextouch.it";
    public const SEND_TO_NAME  = "Andronico";

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
     */
    public function __construct(
        Context $context,
        Validator $formKeyValidator,
        LoggerInterface $logger,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder
    ) {
        parent::__construct($context);
        $this->_formKeyValidator  = $formKeyValidator;
        $this->_logger            = $logger;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder  = $transportBuilder;
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

                $sender = [
                    'email' => "info@andronico.it",
                    'name'  => "Andronico"
                ];

                $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('elightwalk_core_iphone_emailtemplate')
                    ->setTemplateOptions([
                        'area'  => Area::AREA_FRONTEND,
                        'store' => Store::DEFAULT_STORE_ID,
                    ])
                    ->setTemplateVars([
                        'model'   => $post['model'],
                        'name'    => $post['name'],
                        'surname' => $post['surname'],
                        'email'   => $post['email'],
                        'phone'   => $post['phone'],
                    ])
                    ->setFrom($sender)
                    ->addTo(self::SEND_TO_EMAIL, self::SEND_TO_NAME)
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

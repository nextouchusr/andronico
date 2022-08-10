<?php
declare(strict_types=1);

namespace Nextouch\Customer\Plugin\Controller\Account;

use Magento\Customer\Controller\Account\CreatePost;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Nextouch\Customer\Api\Data\CustomerInterface;

class ValidateIsPrivacyPolicyAccepted
{
    private RedirectFactory $resultRedirectFactory;
    private ManagerInterface $messageManager;

    public function __construct(
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
    }

    public function aroundExecute(CreatePost $subject, \Closure $proceed)
    {
        $params = $subject->getRequest()->getParams();

        if (!isset($params[CustomerInterface::IS_PRIVACY_POLICY_ACCEPTED])) {
            $this->messageManager->addErrorMessage(__('The privacy policy must be accepted before you can continue'));
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/create');
        }

        return $proceed();
    }
}

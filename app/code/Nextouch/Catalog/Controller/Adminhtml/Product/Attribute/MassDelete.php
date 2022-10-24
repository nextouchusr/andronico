<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Controller\Adminhtml\Product\Attribute;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class MassDelete extends Action implements HttpPostActionInterface
{
    private string $redirectUrl = '*/*/index';
    private ProductAttributeRepositoryInterface $attributeRepository;

    public function __construct(Context $context, ProductAttributeRepositoryInterface $attributeRepository)
    {
        parent::__construct($context);
        $this->attributeRepository = $attributeRepository;
    }

    public function execute()
    {
        try {
            $attributeCodes = $this->getRequest()->getParam('attribute_codes');

            return $this->massDelete($attributeCodes);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            return $resultRedirect->setPath($this->redirectUrl);
        }
    }

    /**
     * @throws LocalizedException
     */
    private function massDelete(array $attributeCodes)
    {
        $attributesDeleted = 0;
        foreach ($attributeCodes as $attributeCode) {
            $this->attributeRepository->deleteById($attributeCode);
            $attributesDeleted++;
        }

        if ($attributesDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) were deleted.', $attributesDeleted)
            );
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('catalog/*/index');

        return $resultRedirect;
    }
}

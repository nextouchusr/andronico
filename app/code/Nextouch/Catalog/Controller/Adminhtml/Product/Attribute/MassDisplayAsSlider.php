<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Controller\Adminhtml\Product\Attribute;

use Amasty\Shopby\Model\Source\DisplayMode;
use Amasty\ShopbyBase\Api\Data\FilterSettingRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class MassDisplayAsSlider extends Action implements HttpPostActionInterface
{
    private string $redirectUrl = '*/*/index';
    private FilterSettingRepositoryInterface $filterSettingRepository;

    public function __construct(
        Context $context,
        FilterSettingRepositoryInterface $filterSettingRepository
    ) {
        parent::__construct($context);
        $this->filterSettingRepository = $filterSettingRepository;
    }

    public function execute()
    {
        try {
            $attributeCodes = $this->getRequest()->getParam('attribute_codes');

            return $this->massUpdate($attributeCodes);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            return $resultRedirect->setPath($this->redirectUrl);
        }
    }

    /**
     * @throws LocalizedException
     */
    private function massUpdate(array $attributeCodes)
    {
        $attributesUpdated = 0;
        foreach ($attributeCodes as $attributeCode) {
            $filterSetting = $this->filterSettingRepository->getByAttributeCode($attributeCode);
            $filterSetting->setDisplayMode(DisplayMode::MODE_SLIDER);
            $this->filterSettingRepository->save($filterSetting);
            $attributesUpdated++;
        }

        if ($attributesUpdated) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) were updated.', $attributesUpdated)
            );
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('catalog/*/index');

        return $resultRedirect;
    }
}

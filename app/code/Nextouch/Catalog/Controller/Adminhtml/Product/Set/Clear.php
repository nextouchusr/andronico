<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Controller\Adminhtml\Product\Set;

use Magento\Backend\App\Action\Context;
use Magento\Catalog\Controller\Adminhtml\Product\Set;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

class Clear extends Set implements HttpGetActionInterface
{
    private string $redirectUrl = '*/*/index';
    private const ENTITY_TYPE_ID = 4;
    private const DEFAULT_ATTRIBUTE_SET_ID = 4;

    private CollectionFactory $attributeSetCollectionFactory;
    private AttributeSetRepositoryInterface $attributeSetRepository;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CollectionFactory $attributeSetCollectionFactory,
        AttributeSetRepositoryInterface $attributeSetRepository
    ) {
        parent::__construct($context, $coreRegistry);
        $this->attributeSetCollectionFactory = $attributeSetCollectionFactory;
        $this->attributeSetRepository = $attributeSetRepository;
    }

    public function execute()
    {
        try {
            $this->clearAll();

            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('catalog/*/index');

            return $resultRedirect;
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            return $resultRedirect->setPath($this->redirectUrl);
        }
    }

    /**
     * @throws LocalizedException
     */
    private function clearAll(): void
    {
        $collection = $this->attributeSetCollectionFactory->create();
        $collection->addFieldToFilter('entity_type_id', self::ENTITY_TYPE_ID);

        /** @var AttributeSetInterface $attributeSet */
        foreach ($collection->getItems() as $attributeSet) {
            if ((int) $attributeSet->getAttributeSetId() === self::DEFAULT_ATTRIBUTE_SET_ID) {
                continue;
            }

            $this->attributeSetRepository->deleteById($attributeSet->getAttributeSetId());
        }
    }
}

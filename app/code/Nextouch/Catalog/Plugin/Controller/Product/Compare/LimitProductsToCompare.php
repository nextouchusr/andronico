<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Plugin\Controller\Product\Compare;

use Magento\Catalog\Controller\Product\Compare\Add;
use Magento\Catalog\Helper\Product\Compare as CompareHelper;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

class LimitProductsToCompare
{
    private const LIMIT_TO_COMPARE_PRODUCTS = 2;

    private CompareHelper $compareHelper;
    private RedirectFactory $resultRedirectFactory;
    private ManagerInterface $messageManager;

    public function __construct(
        CompareHelper $compareHelper,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager
    ) {
        $this->compareHelper = $compareHelper;
        $this->resultRedirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function aroundExecute(Add $subject, \Closure $proceed)
    {
        $count = $this->compareHelper->getItemCount();

        if ($count >= self::LIMIT_TO_COMPARE_PRODUCTS) {
            $this->messageManager->addErrorMessage(
                __('You can compare up to a maximum of %1 products', self::LIMIT_TO_COMPARE_PRODUCTS)
            );

            return $this->resultRedirectFactory->create()->setRefererOrBaseUrl();
        }

        return $proceed();
    }
}

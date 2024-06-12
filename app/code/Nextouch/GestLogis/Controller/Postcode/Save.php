<?php

namespace Nextouch\GestLogis\Controller\Postcode;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Magento\Framework\Controller\ResultInterface;

class Save extends Action
{
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    /**
     * __construct
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param DataHelper $dataHelper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        DataHelper $dataHelper
    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_dataHelper        = $dataHelper;
    }

    /**
     * Execute
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();

        /* if (!$this->_dataHelper->validateFormKey()->validate($this->getRequest())) {
            $result->setData($this->_dataHelper->invalidFormKeyResponse());
            return $result;
        } */

        $response   = [
            'success' => false,
            'message' => __('Something went wrong!')
        ];

        $postcode       = $this->getRequest()->getParam('postcode');
        //$attributeSetId = $this->getRequest()->getParam('attribute_set_id');
        $productId      = $this->getRequest()->getParam('product_id');

        try {
            if ($postcode) {
                $sessionPostcode      = $this->_dataHelper->getSessionValue(DataHelper::GESTLOGIS_SESSION_POSTCODE);
                $postcodeAvailability = $this->_dataHelper->isPostcodeAvailable($postcode);

                // Check for postcode available in table.
                if (!$postcodeAvailability) {
                    $response['success'] = false;
                    $response['message'] = __("The postcode you have entered is invalid or not available.");
                    return $result->setData($response);
                }

                // Check for postcode available for current product.
                $product        = $this->_dataHelper->getProductById($productId);
                $productAllowed = $this->_dataHelper->isProductAllowedForGestlogis($product);
                if (!$productAllowed) {
                    $response['success'] = false;
                    $response['message'] = __("Standard shipping price is not available for provided postcode.");
                    return $result->setData($response);
                }

                $postcodePrice = $this->_dataHelper->getPostcodePrice($product, $postcode);
                if ($postcodePrice === null) {
                    $response['success'] = false;
                    $response['message'] = __("Postcode Price is not available!");
                    // $response['message'] = __("Product attributes not matched!");
                    return $result->setData($response);
                }

                if ($sessionPostcode && trim($sessionPostcode) != trim($postcode)) {
                    // Clear the cart.
                    $this->_eventManager->dispatch('gestlogis_truncate_cart', ['request' => $this->getRequest()]);
                }

                $this->_dataHelper->unsetSessionValue(DataHelper::GESTLOGIS_SESSION_POSTCODE);
                $this->_dataHelper->setSessionValue(DataHelper::GESTLOGIS_SESSION_POSTCODE, $postcode);

                $response['success'] = true;
                $response['message'] = __("Postcode Successfully Saved.");
            } else {
                // Unset postcode from session.
                $this->_dataHelper->unsetSessionValue(DataHelper::GESTLOGIS_SESSION_POSTCODE);

                $response['success'] = true;
                $response['message'] = __("Postcode Unset Successfully.");
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        } catch (\Error $e) {
            $response['message'] = $e->getMessage();
        }

        return $result->setData($response);
    }
}

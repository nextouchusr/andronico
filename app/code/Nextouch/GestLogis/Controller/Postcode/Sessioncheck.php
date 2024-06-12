<?php

namespace Nextouch\GestLogis\Controller\Postcode;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Nextouch\GestLogis\Helper\Data as DataHelper;
use Magento\Framework\Controller\ResultInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\ResourceConnection;

class Sessioncheck extends Action
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
     * @var CheckoutSession
     */
    protected $_checkoutSession;

    /**
     * @var ResourceConnection
     */
    private $_resourceConnection;

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
        DataHelper $dataHelper,
        CheckoutSession $checkoutSession,
        ResourceConnection $resource
    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_dataHelper = $dataHelper;
        $this->_checkoutSession = $checkoutSession;
        $this->_resourceConnection = $resource;
    }

    /**
     * Execute
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();

        $response = [
            'remove_cookie' => false
        ];

        $quote = $this->_checkoutSession->getQuote();
        $items = $quote->getAllItems();
        $connection = $this->_resourceConnection->getConnection();
        $tableName = $connection->getTableName('nextouch_gestlogis_quote_shipping_service');


        $count = 0;

        foreach ($items as $item) {

            $sql = "SELECT * FROM " . $tableName . " WHERE item_id = " . $item->getId();
            if ($connection->fetchOne($sql)) {
                $count++;
            }
        }

        if ($count == 0 && !$this->_dataHelper->getPostcodeFromSession()) {
            $response['remove_cookie'] = true;
        }


        $result->setData($response);
        return $result;
    }
}
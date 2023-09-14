<?php

declare(strict_types=1);

namespace Elightwalk\Core\Controller\Cart;

use Magento\Checkout\Controller\Cart;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Store\Model\StoreManagerInterface;

class Clear extends Cart
{
    private JsonFactory $resultJsonFactory;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        CustomerCart $cart,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $this->cart->truncate()->save();

        return $this->resultJsonFactory->create()->setData(['data' => true]);
    }
}

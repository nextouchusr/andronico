<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Helper;

use Axepta\Paymentservice\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Magento\Setup\Exception;

use Magento\Catalog\Model\Product\Media\Config;
use Magento\Framework\Filesystem;
use Magento\Framework\Module\ModuleListInterface;

class Data extends AbstractHelper
{

    private $method;
    private $testmode;
    private $payGateway;

    protected $objectManager;

    /**
     * resolver
     *
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $resolver;

    /**
     * urlInterface
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;

    /**
     * storeManager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var Logger
     */
    private $customLogger;

    protected $zendHttpClient;

    protected $_productRepositoryFactory;

    protected $_moduleList;


    /**
     * @var Config
     */
    private $mediaConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Locale\ResolverInterface $resolver,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Logger $customLogger,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        Config $mediaConfig,
        ModuleListInterface $moduleList
    ) {
        parent::__construct($context);
        $this->method = $this->scopeConfig->getValue('payment/axepta_paymentservice/payment_method');
        $this->testmode = $this->scopeConfig->getValue('payment/axepta_paymentservice/testmode');
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->payGateway = $this->objectManager->get('\Axepta\Payment\PayGateway');

        $this->resolver = $resolver;
        $this->storeManager = $storeManager;
        $this->customLogger = $customLogger;

        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->mediaConfig = $mediaConfig;

        $this->_moduleList = $moduleList;
    }

    public function getConfig($field)
    {
        return $this->scopeConfig->getValue(sprintf('payment/axepta_paymentservice/%s', $field));
    }

    public function getPayGateway()
    {
        return $this->payGateway->get(
            $this->method && $this->method !== '' ? $this->method : 'axepta',
            (boolean)$this->testmode
        );
    }

    public function getConfigByCurrentMethod($field)
    {
        return $this->scopeConfig->getValue(sprintf('payment/axepta_paymentservice/%s_%s', $field, $this->method));
    }

    /*** HELPER FUNCTIONS ***/

    public function getMethod()
    {
        return $this->method;
    }

    public function getCheckoutTypes()
    {
        return $this->getPayGateway()->getCheckoutTypes();
    }

    public function log($message)
    {
        if ((boolean)$this->getConfig('enable_log') == true) {
            $this->customLogger->info($message);
        }
    }

    /**
     * Gets store's locale or the `en_US` locale if store's locale does not supported.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->resolver->getLocale();
    }

    /**
     * Gets store's locale currency.
     *
     * @return string
     */
    public function getCurrentCurrency()
    {
        return $this->storeManager->getStore()->getCurrentCurrency();
    }

    /**
     * Gets store's email .
     *
     * @return string
     */
    public function getStoreEmail()
    {
        $this->scopeConfig->getValue(
            'trans_email/ident_sales/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return frontend redirect URL with SID and other session parameters if any
     *
     * @param string $url
     *
     * @return string
     */
    public function getRedirectUrl($url)
    {
        return $this->_urlBuilder->getRedirectUrl($url);
    }

    /**
     * @param Quote $order
     * @param $args
     * @return mixed
     * @throws Exception
     * @throws LocalizedException
     */
    public function init($order, $args = null)
    {
        $date = new \DateTime('now');
        $date->modify('+1 day');

        $argurl = ['_secure' => true, 'orderid' => $order->getReservedOrderId()];

        $billStreet = $order->getBillingAddress()->getStreet();
        $shipStreet = $order->getShippingAddress()->getStreet();

        $products = [];
        $url_images = $this->storeManager->getStore()->getBaseUrl() . $this->storeManager->getStore()->getBaseMediaDir();

        foreach ($order->getAllVisibleItems() as $item) {
            $product = $this->_productRepositoryFactory->create()->getById($item->getProductId());
            $full_path_url = $url_images . '/' . $this->mediaConfig->getMediaPath($product->getThumbnail());
            $product_arr = [
            'logo' => $full_path_url,
            'quantity' => $item->getQty(),
            'description' => $item->getName(),
            'price' => number_format((float)$item->getPrice(), 2, '.', ''),
            ];
            array_push($products, $product_arr);
        }

        $addresses = [];
        array_push($addresses, [
          'type' => 'FATTURAZIONE',
          'addresseeName' => sprintf('%s %s', $order->getBillingAddress()->getFirstname(), $order->getBillingAddress()->getLastname()),
          'streetAddress_1' => $billStreet[0],
          'streetAddress_2' => count($billStreet) > 1 ? $billStreet[1] : '',
          'zip' => $order->getBillingAddress()->getPostcode(),
          'city' => $order->getBillingAddress()->getCity(),
          'provinceState' => $order->getBillingAddress()->getRegion(),
          'country' => $order->getBillingAddress()->getCountry(),
        ]);

        if ($order->getShippingAddress()->getFirstname() !== null) {
            array_push($addresses, [
            'type' => 'SPEDIZIONE',
            'addresseeName' => sprintf('%s %s', $order->getShippingAddress()->getFirstname(), $order->getShippingAddress()->getLastname()),
            'streetAddress_1' => $shipStreet[0],
            'streetAddress_2' => count($shipStreet) > 1 ? $shipStreet[1] : '',
            'zip' => $order->getShippingAddress()->getPostcode(),
            'city' => $order->getShippingAddress()->getCity(),
            'provinceState' => $order->getShippingAddress()->getRegion(),
            'country' => $order->getShippingAddress()->getCountry(),
            ]);
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
        $moduleVersion = $this->_moduleList->getOne('Axepta_Paymentservice')['setup_version'];

        $params = [
          'amount' => number_format((float)$order->getGrandTotal(), 2, '.', ''),
          'currency' => $this->getCurrentCurrency()->getCode(),
          'language' => strtoupper(substr($this->getLocale(), 0, 2)),
          'transaction_type' => $this->getPaymentAction(),
          'notifications' => [
            'name' => sprintf('%s %s', $order->getBillingAddress()->getFirstname(), $order->getBillingAddress()->getLastname()),
            'email' => $order->getCustomerEmail(),
            'smartphone' => $order->getShippingAddress()->getTelephone(),
          ],
          'plugin' => [
            'ecommerce_name' => sprintf('%s - %s - %s', $this->storeManager->getStore()->getName(), $this->storeManager->getStore()->getCode(), $this->storeManager->getStore()->getWebsiteId()),
            'ecommerce_version' => $productMetadata->getVersion(),
            'plugin_version' => $moduleVersion,
            'php_version' => phpversion(),
          ],
          'addresses' => $addresses,
          'products' => $products,
          'redirect_successUrl' => substr($this->_getUrl('axepta/payment/success', $argurl), 0, 512),
          'redirect_failureUrl' => substr($this->_getUrl('axepta/payment/error', $argurl), 0, 512),
          'callback_url' => substr($this->_getUrl('axepta/payment/callback', $argurl), 0, 512),
          'tokenize' => false,
          'accessToken' => $this->getConfig('access_token_axepta'),
          'licenseKey' => $this->getConfig('server_license_key_axepta'),
        ];

        if ($this->getConfig('add_info_1')) {
            $params['addInfo1'] = substr($this->placeholders($this->getConfig('add_info_1'), $order), 0, 256);
        }
        if ($this->getConfig('add_info_2')) {
            $params['addInfo2'] = substr($this->placeholders($this->getConfig('add_info_2'), $order), 0, 256);
        }
        if ($this->getConfig('add_info_3')) {
            $params['addInfo3'] = substr($this->placeholders($this->getConfig('add_info_3'), $order), 0, 256);
        }
        if ($this->getConfig('add_info_4')) {
            $params['addInfo4'] = substr($this->placeholders($this->getConfig('add_info_4'), $order), 0, 256);
        }
        if ($this->getConfig('add_info_5')) {
            $params['addInfo5'] = substr($this->placeholders($this->getConfig('add_info_5'), $order), 0, 256);
        }

        $this->log(sprintf('***** (INIT) Process payment for order: %s', $order->getIncrementId()));
        try {
            $response = $this->getPayGateway()->init($params);

            if ($response['error'] === false) {
                return $response;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(__('Error payment process'));
            }
        } catch (\Exception $exception) {
            $this->throwError($exception);
        }
    }

    public function adapterVerify($object)
    {
        return $object;
    }

    public function verify($quote)
    {
        $urlParams = '';
        if (isset($_GET['Len']) && $_GET['Data']) {
            $urlParams = sprintf('Len=%s&Data=%s', $_GET['Len'], $_GET['Data']);
        } elseif (isset($_POST['Len']) && $_POST['Data']) {
            $urlParams = sprintf('Len=%s&Data=%s', $_POST['Len'], $_POST['Data']);
        }

        $orderReference = $quote->getPayment()->getAdditionalInformation('order_reference');
        $paymentID = $quote->getPayment()->getAdditionalInformation('payment_id');
        $shopID = $quote->getPayment()->getAdditionalInformation('shopID');
        $quoteArr = $quote->toArray();
        $url = '';
        foreach ($_GET as $key => $item) {
            $url .= $key . '=' . $item . '&';
        }
        $url = rtrim($url, '&');
        $params = [
            'orderReference' => $orderReference,
            'paymentID' => $paymentID,
            'shopID' => $shopID,
            'terminalId' => $this->getConfigByCurrentMethod('tid'),
            'hashMessage' => $this->getConfigByCurrentMethod('ksig'),
            'hMacPassword' => $this->getConfig('hmac_password'),
            'language' => substr($this->getLocale(), -2),
            'testmode' => $this->getConfig('testmode'),
            'UrlParams' => $url,
            'outcomeResponse' => isset($quoteArr['']['outcome']) ? $quoteArr['']['outcome'] : '',
            'accessToken' => $this->getConfig('access_token_axepta'),
            'licenseKey' => $this->getConfig('server_license_key_axepta'),
        ];

        try {
            $this->log(sprintf('***** (VERIFY) Verify order: %s', $quote->getId()));
            return $this->getPayGateway()->verify($params);
        } catch (\Exception $exception) {
            $this->throwError($exception);
        }
    }

    public function confirm($data)
    {
        $params = [
            'transactionID' => $data['transaction_id'],
            'paymentId' => $data['payment_id'],
            'amount' => number_format((float)$data['amount'], 2, '.', ''),
            'accessToken' => $this->getConfig('access_token_axepta'),
            'licenseKey' => $this->getConfig('server_license_key_axepta')
        ];

        try {
            $this->log(sprintf('***** (CONFIRM) Confirm payment: %s', $data['payment_id']));
            return $this->getPayGateway()->confirm($params);
        } catch (\Exception $exception) {
            $this->throwError($exception);
        }
    }

    public function void(array $data)
    {
        $params = [
          'transactionID' => $data['transaction_id'],
          'paymentId' => $data['payment_id'],
          'amount' => number_format((float)$data['amount'], 2, '.', ''),
          'accessToken' => $this->getConfig('access_token_axepta'),
          'licenseKey' => $this->getConfig('server_license_key_axepta')
        ];
        try {
            $this->log(sprintf('***** (VOID) Cancel payment: %s', $data['payment_id']));
            return $this->getPayGateway()->cancel($params);
        } catch (\Exception $exception) {
            $this->throwError($exception);
        }
    }

    public function refund(array $data)
    {
        $params = [
            'transactionID' => $data['transaction_id'],
            'paymentId' => $data['payment_id'],
            'amount' => number_format((float)$data['amount'], 2, '.', ''),
            'accessToken' => $this->getConfig('access_token_axepta'),
            'licenseKey' => $this->getConfig('server_license_key_axepta')
        ];
        try {
            $this->log(sprintf('***** (REFUND) Refund payment: %s', $data['payment_id']));
            return $this->getPayGateway()->refund($params);
        } catch (\Exception $exception) {
            $this->throwError($exception);
        }
    }

    private function placeholders($content, $order)
    {
        $content = str_replace('{site_title}', $this->storeManager->getStore()->getName(), $content);
        $content = str_replace('{order_number}', $order->getReservedOrderId(), $content);
        $content = str_replace('{customer_name}', $order->getBillingAddress()->getName(), $content);
        $content = str_replace('{customer_email}', $order->getBillingAddress()->getEmail(), $content);
        return str_replace('{order_date}', $order->getCreatedAt(), $content);
    }

    private function throwError($exception)
    {
        $this->log("------***ERROR***------");
        $this->customLogger->critical($exception->getMessage() . "\n");
        throw new Exception($exception->getMessage());
    }

    public function getPaymentAction()
    {
        $action = '';
        $paymentAction = $this->getConfig('payment_action');
        switch ($paymentAction):
            case 'authorize':
                $action = 'AUTH';
                break;
            case 'authorize_capture':
                $action = 'PURCHASE';
                break;
            case 'verify':
                $action = 'VERIFY';
                break;
            default:
                $action = 'AUTH';
        endswitch;
        return $action;
    }

    public function getSdkUrl()
    {
        return $this->getPayGateway()->getSdkUrl();
    }

    public function getApiUrl()
    {
        return $this->getPayGateway()->getApiUrl();
    }
}

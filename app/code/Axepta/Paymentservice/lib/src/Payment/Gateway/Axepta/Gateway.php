<?php

namespace Payment\Gateway\Axepta;

use HttpException;
use HttpRequest;
use Payment\PaymentUtils;
use Zend\Http\Client;
use Zend\Json\Json;
use Zend_Http_Client;

class Gateway
{
    protected $allowedLanguages;

    protected $testURL = 'https://pay-sandbox.axepta.it';
    protected $liveURL = 'https://pay.axepta.it';

    protected $testSdkURL = 'https://pay-sandbox.axepta.it/sdk/axepta-pg-sdk.js';
    protected $liveSdkURL = 'https://pay.axepta.it/sdk/axepta-pg-sdk.js';

    protected $sdkURL;
    protected $endpoint;

    protected $accessToken;
    protected $objectManager;
    protected $helper;

    // the initializing payment endpoint
    const INIT_PAYMENT_URL = '/api/v1/payment/initPayment';
    const CREDIT_PAYMENT_URL = '/api/v1/payments/credit';
    const CAPTURE_PAYMENT_URL = '/api/v1/payments/confirm';
    const VOID_PAYMENT_URL    = '/api/v1/payments/void';
    const VERIFY_PAYMENT_URL    = '/api/v1/payments/verify';

    public function __construct($test = true)
    {
        $this->endpoint = ($test) ? $this->testURL : $this->liveURL;
        $this->sdkURL = ($test) ? $this->testSdkURL : $this->liveSdkURL;
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->helper = $this->objectManager->get('Axepta\Paymentservice\Helper\Data');
    }

    /** Executes queries to given endpoints in IGFS new gateway
     * @param $endpoint
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    private function execute($endpoint, $params, $method)
    {
        $this->checkGatewayFields($params);
        try {
            $zendClient = new \Zend\Http\Client();

            $this->accessToken = $params['accessToken'];
            unset($params['accessToken']);
            $this->licenseKey = $params['licenseKey'];
            unset($params['licenseKey']);
            $zendClient->reset();
            $zendClient->setUri($endpoint);
            $zendClient->setMethod($method);

            $zendClient->setHeaders(
                [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer $this->accessToken",
                    'X-license-key' => "$this->licenseKey"
                ]
            );
            $zendClient->setRawBody(Json::encode($params));
            $this->helper->log("*** Request ***: \n" . $method . " " . $endpoint . "\n" . Json::encode($params));
            return $zendClient->send();
        } catch (\Zend\Http\Exception\RuntimeException $runtimeException) {
            throw new \UnexpectedValueException($runtimeException->getMessage());
        }
    }

    /**
     * Payment initialization
     * @param array $params
     * @param Client $zendClient
     * @return array
     * @throws \Exception
     */
    public function init(array $params = [])
    {
        $initendpoint = $this->endpoint . self::INIT_PAYMENT_URL;
        try {
            $resp = $this->execute($initendpoint, $params, \Zend\Http\Request::METHOD_POST);
            $this->helper->log("*** Response Init from gtw: \n" . $resp);
            $result = json_decode($resp->getBody());
            return [
              'code' => isset($result->code) ? $result->code : null,
              'message' => isset($result->message) ? $result->message : null,
              'paymentID' => isset($result->paymentID) ? $result->paymentID : null,
              'error' => isset($result->paymentID) ? false : true,
              'statusCode' => $resp->getStatusCode(),
              'orderReference' => null,
              'redirectURL' => null,
            ];
        } catch (\Exception $e) {
            $this->helper->log("*** Exception on Init from gtw:" . $e->getMessage());
            return [
              'message' => "Unexpected Error",
              'error' => true,
            ];
        }
    }

    /**
     * Verification
     * @param array $params
     * @return array
     */
    public function verify($params = [])
    {
        $verifyendpoint = sprintf('%s%s/%s', $this->endpoint, self::VERIFY_PAYMENT_URL, $params['paymentID']);
        try {
            $resp = $this->execute($verifyendpoint, $params, \Zend\Http\Request::METHOD_GET);
            $this->helper->log("*** Response Verify from gtw: \n" . $resp);
            $result = json_decode($resp->getBody());

            return [
            'shopID' => isset($result->shopID) ? $result->shopID : null,
            'terminalId' => isset($result->tid) ? $result->tid : null,
            'returnCode' => isset($result->transaction_code) ? $result->transaction_code : null,
            'paymentID' => isset($result->paymentId) ? $result->paymentId : null,
            'message' => isset($result->description_status) ? $result->description_status : 'Unexpected Response from Gateway',
            'error' => isset($result->transaction_status) ? $result->transaction_status !== 'PG_000' : true,
            'tranID' => isset($result->transactionID) ? $result->transactionID : null,
            'orderReference' => isset($result->shopID) ? $result->shopID : null,
            'brand' => isset($result->brand) ? $result->brand : null,
            'maskedPan' => isset($result->maskedPan) ? $result->maskedPan : null,
            'authCode' => isset($result->authCode) ? $result->authCode : null,
            ];

        } catch (\Exception $e) {
            $this->helper->log("*** Exception on Verify from gtw:" . $e->getMessage());
            return [
            'message' => "Unexpected Error",
            'error' => true,
            ];
        }
    }

    /**
     * @param array $params
     * @throws \Exception
     */
    public function confirm($params = [])
    {

        $confirmendpoint = $this->endpoint . self::CAPTURE_PAYMENT_URL;

        try {
            $resp = $this->execute($confirmendpoint, $params, \Zend\Http\Request::METHOD_POST); // do call
            $this->helper->log("*** Response Confirm from gtw: \n" . $resp);
            $result = json_decode($resp->getBody());

            return [
            'terminalId' => isset($result->tid) ? $result->tid : null,
            'returnCode' => isset($result->authCode) ? $result->authCode : null,
            'message' => isset($result->description_status) ? $result->description_status : null,
            'error' => isset($result->transaction_status) ? $result->transaction_status !== 'PG_000' : true,
            'refTranID' => isset($result->transactionID) ? $result->transactionID : null,
            'tranID' => isset($result->transactionID) ? $result->transactionID : null,
            'pendingAmount' => isset($result->pendingAmount) ? $result->pendingAmount : null,
            ];
        } catch (\Exception $e) {
            $this->helper->log("*** Exception on confirm from gtw:" . $e->getMessage());
            return [
            'message' => "Unexpected Error",
            'error' => true,
            ];
        }
    }

    /** Credit (refund) of amount for order
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function refund($params = [])
    {
        $refundendpoint = $this->endpoint . self::CREDIT_PAYMENT_URL;
        try {
            $resp = $this->execute($refundendpoint, $params, \Zend\Http\Request::METHOD_POST);
            $this->helper->log("*** Response Refund from gtw: \n" . $resp);
            $result = json_decode($resp->getBody());

            return [
              'operation_type' => isset($result->operation_type) ? $result->operation_type : null,
              'message' => isset($result->description_status) ? $result->description_status : null,
              'paymentID' => isset($result->paymentId) ? $result->paymentId : null,
              'error' => isset($result->transaction_status) ? $result->transaction_status !== 'PG_000' : true,
              'transaction_code' => isset($result->transaction_code) ? $result->transaction_code : null,
              'statusCode' => $resp->getStatusCode(),
              'transactionAt' => isset($result->transactionAt) ? $result->transactionAt : null,
              'pendingAmount' => isset($result->pendingAmount) ? $result->pendingAmount : null,
              'tranID' => isset($result->transactionID) ? $result->transactionID : null,
            ];
        } catch (\Exception $e) {
            $this->helper->log("*** Exception on Refund from gtw: " . $e->getMessage());
            return [
              'message' => "Unexpected Error",
              'error' => true,
            ];
        }
    }

    /**
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function cancel($params = [])
    {
        $voidendpoint = $this->endpoint . self::VOID_PAYMENT_URL;
        try {
            $resp = $this->execute($voidendpoint, $params, \Zend\Http\Request::METHOD_POST);
            $this->helper->log("*** Response Cancel from gtw: \n" . $resp);
            $result = json_decode($resp->getBody());
            return [
              'terminalId' => isset($result->tid) ? $result->tid : null,
              'orderReference' => isset($result->shopID) ? $result->shopID : null,
              'tranID' => isset($result->transactionID) ? $result->transactionID : null,
              'refTranID' => isset($result->transactionID) ? $result->transactionID : null,
              'returnCode' => isset($result->authCode) ? $result->authCode : null,
              'message' => isset($result->description_status) ? $result->description_status : null,
              'error' => isset($result->transaction_status) ? $result->transaction_status !== 'PG_000' : true,
            ];
        } catch (\Exception $e) {
            $this->helper->log("*** Exception on Cancel from gtw: " . $e->getMessage());
            return [
              'message' => "Unexpected Error",
              'error' => true,
            ];
        }
    }

    /**
     *
     * Return all the possible checkout types
     *
     * @return array|object
     */
    public function getCheckoutTypes()
    {
        return [
            '1' => PaymentUtils::getLabelText('CHECK_OUT_TYPE_1'),
            '3' => PaymentUtils::getLabelText('CHECK_OUT_TYPE_3'),
        ];
    }

    public function getTransactionTypes()
    {
        return [
            'PURCHASE' => PaymentUtils::getLabelText('TRANSACTION_TYPE_PURCHASE'),
            'AUTH' => PaymentUtils::getLabelText('TRANSACTION_TYPE_AUTH'),
            'VERIFY' => PaymentUtils::getLabelText('TRANSACTION_TYPE_VERIFY'),
        ];
    }

    public function getPaymentInstruments()
    {
        return [];
    }

    public function getApiUrl()
    {
        return $this->endpoint;
    }

    public function getSdkUrl()
    {
        return $this->sdkURL;
    }

    /** Throws error if some of necessary parameters for merchant configuration are missing
     * @param $params
     * @throws \Exception
     */
    private function checkGatewayFields($params)
    {
        if (!isset($params['accessToken']) || empty($params['accessToken'])) {
            throw new \InvalidArgumentException('Access Token is required!');
        }
        if (!isset($params['licenseKey']) || empty($params['licenseKey'])) {
            throw new \InvalidArgumentException('License key is required!');
        }
    }
}

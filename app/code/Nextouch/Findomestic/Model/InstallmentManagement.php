<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model;

use Laminas\Http\Request;
use Nextouch\Findomestic\Api\InstallmentManagementInterface;
use Nextouch\Findomestic\Model\Request\Installment\Activate as ActivateRequest;
use Nextouch\Findomestic\Model\Request\Installment\Cancel as CancelRequest;
use Nextouch\Findomestic\Model\Request\Installment\Create as CreateRequest;
use Nextouch\Findomestic\Model\Request\Installment\Refresh as RefreshRequest;
use Nextouch\Findomestic\Model\Request\Installment\Refund as RefundRequest;
use Nextouch\Findomestic\Model\Response\Installment\Activate as ActivateResponse;
use Nextouch\Findomestic\Model\Response\Installment\Cancel as CancelResponse;
use Nextouch\Findomestic\Model\Response\Installment\Create as CreateResponse;
use Nextouch\Findomestic\Model\Response\Installment\Refresh as RefreshResponse;
use Nextouch\Findomestic\Model\Response\Installment\Refund as RefundResponse;

class InstallmentManagement extends AbstractBaseRestApi implements InstallmentManagementInterface
{
    private const REQUEST_ENDPOINT = '/installments';
    private const CREATE_ACTION = '/create';
    private const REFRESH_ACTION = '/refresh';
    private const ACTIVATE_ACTION = '/activate';
    private const CANCEL_ACTION = '/cancel';
    private const REFUND_ACTION = '/refund';

    public function create(CreateRequest $request): CreateResponse
    {
        $uriEndpoint = $this->config->getBasePath() . self::REQUEST_ENDPOINT . self::CREATE_ACTION;
        $params = ['json' => $request->toArray()];

        $this->logger->info(__('Calling [Create Findomestic installment] with params: %1', json_encode($params['json'])));

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if (!in_array($response->getStatusCode(), self::SUCCESS_STATUSES)) {
            return CreateResponse::fromError([
                'errorCode' => $response->getStatusCode(),
                'errorDescription' => $response->getReasonPhrase(),
            ]);
        }

        $responseBody = $response->getBody();
        $responseContent = $responseBody->getContents();
        $data = $this->jsonSerializer->unserialize($responseContent);

        return CreateResponse::fromArray($data);
    }

    public function refresh(RefreshRequest $request): RefreshResponse
    {
        $uriEndpoint = $this->config->getBasePath() . self::REQUEST_ENDPOINT . self::REFRESH_ACTION;
        $params = ['json' => $request->toArray()];

        $this->logger->info(__('Calling [Refresh Findomestic installment] with params: %1', json_encode($params['json'])));

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if (!in_array($response->getStatusCode(), self::SUCCESS_STATUSES)) {
            return RefreshResponse::fromError([
                'errorCode' => $response->getStatusCode(),
                'errorDescription' => $response->getReasonPhrase(),
            ]);
        }

        $responseBody = $response->getBody();
        $responseContent = $responseBody->getContents();
        $data = $this->jsonSerializer->unserialize($responseContent);

        return RefreshResponse::fromArray($data);
    }

    public function activate(ActivateRequest $request): ActivateResponse
    {
        $uriEndpoint = sprintf(
            '%s/%s',
            $this->config->getBasePath() . self::REQUEST_ENDPOINT,
            $request->getIssuerInstallmentId() . self::ACTIVATE_ACTION
        );

        $params = ['json' => $request->toArray()];

        $this->logger->info(__('Calling [Activate Findomestic installment] with params: %1', json_encode($params['json'])));

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if (!in_array($response->getStatusCode(), self::SUCCESS_STATUSES)) {
            return ActivateResponse::fromError([
                'errorCode' => $response->getStatusCode(),
                'errorDescription' => $response->getReasonPhrase(),
            ]);
        }

        $responseBody = $response->getBody();
        $responseContent = $responseBody->getContents();
        $data = $this->jsonSerializer->unserialize($responseContent);

        return ActivateResponse::fromArray($data);
    }

    public function cancel(CancelRequest $request): CancelResponse
    {
        $uriEndpoint = sprintf(
            '%s/%s',
            $this->config->getBasePath() . self::REQUEST_ENDPOINT,
            $request->getIssuerInstallmentId() . self::CANCEL_ACTION
        );

        $params = ['json' => $request->toArray()];

        $this->logger->info(__('Calling [Cancel Findomestic installment] with params: %1', json_encode($params['json'])));

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if (!in_array($response->getStatusCode(), self::SUCCESS_STATUSES)) {
            return CancelResponse::fromError([
                'errorCode' => $response->getStatusCode(),
                'errorDescription' => $response->getReasonPhrase(),
            ]);
        }

        $responseBody = $response->getBody();
        $responseContent = $responseBody->getContents();
        $data = $this->jsonSerializer->unserialize($responseContent);

        return CancelResponse::fromArray($data);
    }

    public function refund(RefundRequest $request): RefundResponse
    {
        $uriEndpoint = sprintf(
            '%s/%s',
            $this->config->getBasePath() . self::REQUEST_ENDPOINT,
            $request->getIssuerInstallmentId() . self::REFUND_ACTION
        );

        $params = ['json' => $request->toArray()];

        $this->logger->info(__('Calling [Refund Findomestic installment] with params: %1', json_encode($params['json'])));

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if (!in_array($response->getStatusCode(), self::SUCCESS_STATUSES)) {
            return RefundResponse::fromError([
                'errorCode' => $response->getStatusCode(),
                'errorDescription' => $response->getReasonPhrase(),
            ]);
        }

        $responseBody = $response->getBody();
        $responseContent = $responseBody->getContents();
        $data = $this->jsonSerializer->unserialize($responseContent);

        return RefundResponse::fromArray($data);
    }
}

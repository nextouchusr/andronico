<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model;

use Laminas\Http\Request;
use Nextouch\Findomestic\Api\InstallmentManagementInterface;
use Nextouch\Findomestic\Model\Request\Installment\Activate as ActivateRequest;
use Nextouch\Findomestic\Model\Request\Installment\Cancel as CancelRequest;
use Nextouch\Findomestic\Model\Request\Installment\Create as CreateRequest;
use Nextouch\Findomestic\Model\Request\Installment\Refresh as RefreshRequest;
use Nextouch\Findomestic\Model\Response\Installment\Activate as ActivateResponse;
use Nextouch\Findomestic\Model\Response\Installment\Cancel as CancelResponse;
use Nextouch\Findomestic\Model\Response\Installment\Create as CreateResponse;
use Nextouch\Findomestic\Model\Response\Installment\Refresh as RefreshResponse;

class InstallmentManagement extends AbstractBaseRestApi implements InstallmentManagementInterface
{
    private const REQUEST_ENDPOINT = '/installments';
    private const CREATE_ACTION = '/create';
    private const REFRESH_ACTION = '/refresh';
    private const ACTIVATE_ACTION = '/activate';
    private const CANCEL_ACTION = '/cancel';

    public function create(CreateRequest $request): CreateResponse
    {
        $uriEndpoint = $this->config->getBasePath() . self::REQUEST_ENDPOINT . self::CREATE_ACTION;
        $params = ['json' => $request->toArray()];

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if ($response->getStatusCode() !== self::HTTP_CREATED) {
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

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if ($response->getStatusCode() !== self::HTTP_CREATED) {
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

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if ($response->getStatusCode() !== self::HTTP_CREATED) {
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

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if ($response->getStatusCode() !== self::HTTP_CREATED) {
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
}

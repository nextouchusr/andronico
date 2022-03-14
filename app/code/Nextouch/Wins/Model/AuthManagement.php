<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model;

use Magento\Framework\Webapi\Request;
use Nextouch\Wins\Api\AuthManagementInterface;
use Nextouch\Wins\Model\Request\Auth\Authorize as AuthorizeRequest;
use Nextouch\Wins\Model\Response\Auth\Authorize as AuthorizeResponse;

class AuthManagement extends AbstractBaseRestApi implements AuthManagementInterface
{
    private const REQUEST_ENDPOINT = '/api/Authentication';
    private const AUTHORIZE_ACTION = '/authorize';

    public function authorize(AuthorizeRequest $request): AuthorizeResponse
    {
        $uriEndpoint = self::REQUEST_ENDPOINT . self::AUTHORIZE_ACTION;
        $params = ['json' => $request->toArray()];

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        if ($response->getStatusCode() !== self::HTTP_OK) {
            return AuthorizeResponse::fromError([
                'errorCode' => $response->getStatusCode(),
                'errorDescription' => $response->getReasonPhrase(),
            ]);
        }

        $responseBody = $response->getBody();
        $responseContent = $responseBody->getContents();
        $data = $this->jsonSerializer->unserialize($responseContent);

        return AuthorizeResponse::fromArray($data);
    }
}

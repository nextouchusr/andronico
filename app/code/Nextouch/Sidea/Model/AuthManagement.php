<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Model;

use Magento\Framework\Webapi\Request;
use Nextouch\Sidea\Api\AuthManagementInterface;
use Nextouch\Sidea\Model\Request\Auth\Authorize as AuthorizeRequest;
use Nextouch\Sidea\Model\Response\Auth\Authorize as AuthorizeResponse;

class AuthManagement extends AbstractBaseRestApi implements AuthManagementInterface
{
    private const AUTHORIZE_ACTION = '/v2/Token';

    public function authorize(AuthorizeRequest $request): AuthorizeResponse
    {
        $uriEndpoint = self::AUTHORIZE_ACTION;
        $params = ['json' => $request->toArray()];

        $response = $this->doRequest(
            $this->config->getAuthBaseUrl(),
            $uriEndpoint,
            $params,
            Request::METHOD_POST
        );

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

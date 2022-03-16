<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\Wins\Api\ProductSalableQtyManagementInterface;
use Nextouch\Wins\Model\Request\Inventory\GetProductAvailability as GetProductAvailabilityRequest;
use Nextouch\Wins\Model\Response\Inventory\GetProductAvailability as GetProductAvailabilityResponse;

class ProductSalableQtyManagement extends AbstractBaseRestApi implements ProductSalableQtyManagementInterface
{
    private const REQUEST_ENDPOINT = '/api/Magento';
    private const GET_PRODUCT_AVAILABILITY_ACTION = '/GetProductAvailability';

    /**
     * @throws InvalidArgumentException
     */
    public function getProductAvailability(GetProductAvailabilityRequest $request): GetProductAvailabilityResponse
    {
        $uriEndpoint = self::REQUEST_ENDPOINT . self::GET_PRODUCT_AVAILABILITY_ACTION;
        $params = [
            'headers' => ['Authorization' => 'Bearer ' . $request->getAccessToken()],
            'json' => $request->toArray(),
        ];

        $response = $this->doRequest($uriEndpoint, $params);

        if ($response->getStatusCode() !== self::HTTP_OK) {
            return GetProductAvailabilityResponse::fromError([
                'errorCode' => $response->getStatusCode(),
                'errorDescription' => $response->getReasonPhrase(),
            ]);
        }

        $responseBody = $response->getBody();
        $responseContent = $responseBody->getContents();
        $data = $this->jsonSerializer->unserialize($responseContent);

        return GetProductAvailabilityResponse::fromArray($data);
    }
}

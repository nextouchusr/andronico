<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model;

use Magento\Framework\Webapi\Request;
use Nextouch\Wins\Api\RmaRepositoryInterface;
use Nextouch\Wins\Model\Request\Rma\CreateReturn;

class RmaRepository extends AbstractBaseRestApi implements RmaRepositoryInterface
{
    private const REQUEST_ENDPOINT = '/api/Magento';
    private const CREATE_RETURN_ACTION = '/CreateReturn';

    public function create(CreateReturn $request): bool
    {
        $this->logger->info(__('Create Wins return: %1', $request->getReturn()->getIncrementId()));

        $uriEndpoint = self::REQUEST_ENDPOINT . self::CREATE_RETURN_ACTION;
        $params = [
            'headers' => ['Authorization' => 'Bearer ' . $request->getAccessToken()],
            'json' => $request->toArray(),
        ];

        $this->logger->info(__('Calling [Create Wins return] with params: %1', json_encode($params)));

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        return in_array($response->getStatusCode(), self::SUCCESS_STATUSES);
    }
}

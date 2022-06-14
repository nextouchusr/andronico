<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model;

use Magento\Framework\Webapi\Request;
use Nextouch\Wins\Api\OrderRepositoryInterface;
use Nextouch\Wins\Model\Request\Order\CreateOrder;

class OrderRepository extends AbstractBaseRestApi implements OrderRepositoryInterface
{
    private const REQUEST_ENDPOINT = '/api/Magento';
    private const CREATE_ORDER_ACTION = '/CreateOrder';

    public function create(CreateOrder $request): bool
    {
        $this->logger->info(__('Create Wins order: %1', $request->getOrder()->getIncrementId()));

        $uriEndpoint = self::REQUEST_ENDPOINT . self::CREATE_ORDER_ACTION;
        $params = [
            'headers' => ['Authorization' => 'Bearer ' . $request->getAccessToken()],
            'json' => $request->toArray(),
        ];

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        return in_array($response->getStatusCode(), self::SUCCESS_STATUSES);
    }
}

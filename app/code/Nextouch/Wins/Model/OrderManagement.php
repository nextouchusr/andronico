<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model;

use Magento\Framework\Webapi\Request;
use Nextouch\Wins\Api\OrderManagementInterface;
use Nextouch\Wins\Model\Request\Order\UpdateOrderState;
use Nextouch\Wins\Model\Request\Order\UpdateOrderStatus;

class OrderManagement extends AbstractBaseRestApi implements OrderManagementInterface
{
    private const REQUEST_ENDPOINT = '/api/Magento';
    private const UPDATE_ORDER_STATUS_ACTION = '/UpdateOrderStatus';
    private const UPDATE_ORDER_STATE_ACTION = '/UpdateOrderState';

    public function updateStatus(UpdateOrderStatus $request): bool
    {
        $this->logger->info(__('Update Wins order status: #%1(%2)', $request->getIncrementId(), $request->getStatus()));

        $uriEndpoint = self::REQUEST_ENDPOINT . self::UPDATE_ORDER_STATUS_ACTION;
        $params = [
            'headers' => ['Authorization' => 'Bearer ' . $request->getAccessToken()],
            'json' => $request->toArray(),
        ];

        $this->logger->info(__('Calling [Update Wins order status] with params: %1', json_encode($params)));

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        return in_array($response->getStatusCode(), self::SUCCESS_STATUSES);
    }

    public function updateState(UpdateOrderState $request): bool
    {
        $this->logger->info(__('Update Wins order state: #%1(%2)', $request->getIncrementId(), $request->getState()));

        $uriEndpoint = self::REQUEST_ENDPOINT . self::UPDATE_ORDER_STATE_ACTION;
        $params = [
            'headers' => ['Authorization' => 'Bearer ' . $request->getAccessToken()],
            'json' => $request->toArray(),
        ];

        $this->logger->info(__('Calling [Update Wins order state] with params: %1', json_encode($params)));

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        return in_array($response->getStatusCode(), self::SUCCESS_STATUSES);
    }
}

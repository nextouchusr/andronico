<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Model;

use Magento\Framework\Webapi\Request;
use Nextouch\Sidea\Api\EventManagementInterface;
use Nextouch\Sidea\Model\Request\Event\AbandonedCart as AbandonedCartRequest;

class EventManagement extends AbstractBaseRestApi implements EventManagementInterface
{
    private const REQUEST_ENDPOINT = '/interaction/v1';
    private const SEND_EVENT_ACTION = '/events';

    public function sendAbandonedCart(AbandonedCartRequest $request): bool
    {
        $this->logger->info(__('Send abandoned cart: %1', $request->getData()->getCartId()));

        $uriEndpoint = self::REQUEST_ENDPOINT . self::SEND_EVENT_ACTION;
        $params = [
            'headers' => ['Authorization' => 'Bearer ' . $request->getAccessToken()],
            'json' => $request->toArray(),
        ];

        $response = $this->doRequest(
            $this->config->getEventsBaseUrl(),
            $uriEndpoint,
            $params,
            Request::METHOD_POST
        );

        return in_array($response->getStatusCode(), self::SUCCESS_STATUSES);
    }
}

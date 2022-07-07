<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model;

use Magento\Framework\Webapi\Request;
use Nextouch\Wins\Api\CustomerRepositoryInterface;
use Nextouch\Wins\Model\Request\Customer\UpsertCustomer;

class CustomerRepository extends AbstractBaseRestApi implements CustomerRepositoryInterface
{
    private const REQUEST_ENDPOINT = '/api/Magento';
    private const UPSERT_CUSTOMER_ACTION = '/Customers';

    public function upsert(UpsertCustomer $request): bool
    {
        $this->logger->info(__('Create/Update Wins customer: %1', $request->getCustomer()->getId()));

        $uriEndpoint = self::REQUEST_ENDPOINT . self::UPSERT_CUSTOMER_ACTION;
        $params = [
            'headers' => ['Authorization' => 'Bearer ' . $request->getAccessToken()],
            'json' => $request->toArray(),
        ];

        $response = $this->doRequest($uriEndpoint, $params, Request::METHOD_POST);

        return in_array($response->getStatusCode(), self::SUCCESS_STATUSES);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Model;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\Webapi\Rest\Request;
use Nextouch\Sidea\Helper\SideaConfig;
use Psr\Log\LoggerInterface;

abstract class AbstractBaseRestApi
{
    protected const HTTP_OK = 200;
    protected const HTTP_CREATED = 201;
    protected const HTTP_NO_CONTENT = 204;
    protected const SUCCESS_STATUSES = [self::HTTP_OK, self::HTTP_CREATED, self::HTTP_NO_CONTENT];

    protected ResponseFactory $responseFactory;
    protected ClientFactory $clientFactory;
    protected JsonSerializer $jsonSerializer;
    protected SideaConfig $config;
    protected LoggerInterface $logger;

    public function __construct(
        ResponseFactory $responseFactory,
        ClientFactory $clientFactory,
        JsonSerializer $jsonSerializer,
        SideaConfig $config,
        LoggerInterface $logger
    ) {
        $this->responseFactory = $responseFactory;
        $this->clientFactory = $clientFactory;
        $this->jsonSerializer = $jsonSerializer;
        $this->config = $config;
        $this->logger = $logger;
    }

    protected function doRequest(
        string $baseUri,
        string $uriEndpoint,
        array $params = [],
        string $requestMethod = Request::HTTP_METHOD_GET
    ): Response {
        $client = $this->clientFactory->create([
            'config' => [
                'base_uri' => $baseUri,
            ],
        ]);

        try {
            $response = $client->request(
                $requestMethod,
                $uriEndpoint,
                $params
            );
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage());

            $response = $this->responseFactory->create([
                'status' => $e->getCode(),
                'reason' => $e->getMessage(),
            ]);
        }

        return $response;
    }
}

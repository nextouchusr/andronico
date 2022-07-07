<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Wins\Api\AuthManagementInterface;
use Nextouch\Wins\Api\OrderRepositoryInterface as WinsOrderRepositoryInterface;
use Nextouch\Wins\Helper\WinsConfig;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Request\Auth\Authorize;
use Nextouch\Wins\Model\Request\Order\CreateOrder;
use Nextouch\Wins\Service\Order\Item\CustomOptionsProcessor;

class CreateNewOrder
{
    private OrderRepositoryInterface $orderRepository;
    private AuthManagementInterface $authManagement;
    private WinsOrderRepositoryInterface $winsOrderRepository;
    private CustomOptionsProcessor $customOptionsProcessor;
    private WinsConfig $config;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        AuthManagementInterface $authManagement,
        WinsOrderRepositoryInterface $winsOrderRepository,
        CustomOptionsProcessor $customOptionsProcessor,
        WinsConfig $config
    ) {
        $this->orderRepository = $orderRepository;
        $this->authManagement = $authManagement;
        $this->winsOrderRepository = $winsOrderRepository;
        $this->customOptionsProcessor = $customOptionsProcessor;
        $this->config = $config;
    }

    public function create(Order $order): void
    {
        $isSuccess = $this->createOrder($order);

        if ($isSuccess) {
            $order->setData(OrderInterface::ORDER_SYNC_FAILURES, 0);
        } else {
            $failures = (int) $order->getData(OrderInterface::ORDER_SYNC_FAILURES);
            $order->setData(OrderInterface::ORDER_SYNC_FAILURES, ++$failures);
        }

        $this->orderRepository->save($order);
    }

    private function createOrder(Order $order): bool
    {
        $customOptions = $this->customOptionsProcessor->getAllCustomOptions($order);
        $items = array_merge($order->getItems(), $customOptions);
        $order->setItems($items);

        $authorizeReq = new Authorize($this->config->getAuthUsername(), $this->config->getAuthPassword());
        $authorizeRes = $this->authManagement->authorize($authorizeReq);

        $loginInfo = LoginInfo::fromArray([
            'user' => $this->config->getMagentoUsername(),
            'password' => $this->config->getMagentoPassword(),
        ]);

        $createOrder = new CreateOrder(
            $authorizeRes->getAccessToken(),
            $loginInfo,
            $order
        );

        return $this->winsOrderRepository->create($createOrder);
    }
}

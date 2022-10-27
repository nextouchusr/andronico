<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order;

use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\Wins\Api\AuthManagementInterface;
use Nextouch\Wins\Api\OrderManagementInterface;
use Nextouch\Wins\Helper\WinsConfig;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Request\Auth\Authorize;
use Nextouch\Wins\Model\Request\Order\UpdateOrderState;
use Nextouch\Wins\Model\Request\Order\UpdateOrderStatus;

class SendCurrentOrderStatus
{
    private OrderManagementInterface $orderManagement;
    private AuthManagementInterface $authManagement;
    private WinsConfig $config;

    public function __construct(
        OrderManagementInterface $orderManagement,
        AuthManagementInterface $authManagement,
        WinsConfig $config
    ) {
        $this->orderManagement = $orderManagement;
        $this->authManagement = $authManagement;
        $this->config = $config;
    }

    public function execute(OrderInterface $order, ?string $transactionID = null, ?string $trackingLink = null): void
    {
        $authorizeReq = new Authorize($this->config->getAuthUsername(), $this->config->getAuthPassword());
        $authorizeRes = $this->authManagement->authorize($authorizeReq);

        $loginInfo = LoginInfo::fromArray([
            'user' => $this->config->getMagentoUsername(),
            'password' => $this->config->getMagentoPassword(),
        ]);

        $updateOrderStatus = new UpdateOrderStatus(
            $authorizeRes->getAccessToken(),
            $loginInfo,
            $order->getIncrementId(),
            $order->getStatus(),
            $transactionID,
            $trackingLink
        );

        $updateOrderState = new UpdateOrderState(
            $authorizeRes->getAccessToken(),
            $loginInfo,
            $order->getIncrementId(),
            $order->getState()
        );

        $this->orderManagement->updateStatus($updateOrderStatus);
        $this->orderManagement->updateState($updateOrderState);
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Service;

use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\FastEst\Helper\FastEstConfig;
use Nextouch\Wins\Api\AuthManagementInterface;
use Nextouch\Wins\Api\OrderManagementInterface;
use Nextouch\Wins\Helper\WinsConfig;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Request\Auth\Authorize;
use Nextouch\Wins\Model\Request\Order\UpdateOrderState;
use Nextouch\Wins\Model\Request\Order\UpdateOrderStatus;

class SendTrackingLink
{
    private OrderManagementInterface $orderManagement;
    private AuthManagementInterface $authManagement;
    private WinsConfig $winsConfig;
    private FastEstConfig $fastEstConfig;

    public function __construct(
        OrderManagementInterface $orderManagement,
        AuthManagementInterface $authManagement,
        WinsConfig $winsConfig,
        FastEstConfig $fastEstConfig
    ) {
        $this->orderManagement = $orderManagement;
        $this->authManagement = $authManagement;
        $this->winsConfig = $winsConfig;
        $this->fastEstConfig = $fastEstConfig;
    }

    public function execute(OrderInterface $order): void
    {
        $authorizeReq = new Authorize($this->winsConfig->getAuthUsername(), $this->winsConfig->getAuthPassword());
        $authorizeRes = $this->authManagement->authorize($authorizeReq);

        $loginInfo = LoginInfo::fromArray([
            'user' => $this->winsConfig->getMagentoUsername(),
            'password' => $this->winsConfig->getMagentoPassword(),
        ]);

        $updateOrderStatus = new UpdateOrderStatus(
            $authorizeRes->getAccessToken(),
            $loginInfo,
            $order->getIncrementId(),
            $order->getStatus(),
            null,
            $this->fastEstConfig->getTrackingLink($order->getIncrementId())
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

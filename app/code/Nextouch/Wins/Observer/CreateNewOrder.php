<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\Data\OrderInterface as NextouchOrderInterface;
use Nextouch\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Nextouch\Wins\Api\AuthManagementInterface;
use Nextouch\Wins\Api\OrderRepositoryInterface;
use Nextouch\Wins\Helper\WinsConfig;
use Nextouch\Wins\Model\Request\Auth\Authorize;
use Nextouch\Wins\Model\Request\Order\CreateOrder;

class CreateNewOrder implements ObserverInterface
{
    private OrderCollectionFactory $orderCollectionFactory;
    private AuthManagementInterface $authManagement;
    private OrderRepositoryInterface $orderRepository;
    private WinsConfig $config;

    public function __construct(
        OrderCollectionFactory $orderCollectionFactory,
        AuthManagementInterface $authManagement,
        OrderRepositoryInterface $orderRepository,
        WinsConfig $config
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->authManagement = $authManagement;
        $this->orderRepository = $orderRepository;
        $this->config = $config;
    }

    public function execute(Observer $observer): void
    {
        /** @var OrderInterface $order */
        $order = $observer->getData('order');

        /** @var NextouchOrderInterface $nextouchOrder */
        $nextouchOrder = $this->orderCollectionFactory->create()->getItemById($order->getEntityId());

        $authorizeReq = new Authorize($this->config->getUsername(), $this->config->getPassword());
        $authorizeRes = $this->authManagement->authorize($authorizeReq);

        $createOrderReq = new CreateOrder(
            $authorizeRes->getAccessToken(),
            $authorizeRes->getLoginInfo(),
            $nextouchOrder
        );

        $this->orderRepository->create($createOrderReq);
    }
}

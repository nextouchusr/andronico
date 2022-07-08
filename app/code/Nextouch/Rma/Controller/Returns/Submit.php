<?php
declare(strict_types=1);

namespace Nextouch\Rma\Controller\Returns;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Rma\Api\Data\RmaInterface;
use Magento\Rma\Controller\Returns;
use Magento\Rma\Helper\Data;
use Magento\Rma\Model\Rma;
use Magento\Rma\Model\Rma\Source\Status;
use Magento\Rma\Model\Rma\Status\HistoryFactory;
use Magento\Rma\Model\RmaFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderRepository;
use Nextouch\Rma\Api\RmaRepositoryInterface;
use Psr\Log\LoggerInterface;

class Submit extends Returns
{
    private RmaFactory $rmaModelFactory;
    private OrderRepository $orderRepository;
    private LoggerInterface $logger;
    private DateTime $dateTime;
    private HistoryFactory $statusHistoryFactory;
    private RmaRepositoryInterface $rmaRepository;
    private Data $rmaHelper;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        RmaFactory $rmaModelFactory,
        OrderRepository $orderRepository,
        LoggerInterface $logger,
        DateTime $dateTime,
        HistoryFactory $statusHistoryFactory,
        RmaRepositoryInterface $rmaRepository,
        ?Data $rmaHelper = null
    ) {
        $this->rmaModelFactory = $rmaModelFactory;
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
        $this->dateTime = $dateTime;
        $this->statusHistoryFactory = $statusHistoryFactory;
        parent::__construct($context, $coreRegistry);
        $this->rmaRepository = $rmaRepository;
        $this->rmaHelper = $rmaHelper ?: $this->_objectManager->create(Data::class);
    }

    public function execute()
    {
        $orderId = (int) $this->getRequest()->getParam('order_id');
        $post = $this->getRequest()->getPostValue();

        if (!$this->rmaHelper->canCreateRma($orderId)) {
            return $this->resultRedirectFactory->create()->setPath('*/*/create', ['order_id' => $orderId]);
        }

        if ($post && !empty($post['items'])) {
            try {
                /** @var Order $order */
                $order = $this->orderRepository->get($orderId);

                if (!$this->_canViewOrder($order)) {
                    return $this->_redirect('sales/order/history');
                }

                /** @var Rma $rmaObject */
                $rmaObject = $this->buildRma($order, $post);
                if (!$rmaObject->saveRma($post)) {
                    $url = $this->_url->getUrl('*/*/create', ['order_id' => $orderId]);

                    return $this->getResponse()->setRedirect($this->_redirect->error($url));
                }

                $statusHistory = $this->statusHistoryFactory->create();
                $statusHistory->setRmaEntityId($rmaObject->getEntityId());
                $statusHistory->sendNewRmaEmail();
                $statusHistory->saveSystemComment();

                if (isset($post['rma_comment']) && !empty($post['rma_comment'])) {
                    $comment = $this->statusHistoryFactory->create();
                    $comment->setRmaEntityId($rmaObject->getEntityId());
                    $comment->saveComment($post['rma_comment'], true, false);
                }

                $successMessage = __('You submitted Return #%1.', $rmaObject->getIncrementId());
                $successUrl = $this->_redirect->success($this->_url->getUrl('magento_rma/returns/history'));

                $this->messageManager->addSuccessMessage($successMessage);

                $return = $this->rmaRepository->get((int) $rmaObject->getEntityId());

                $this->_eventManager->dispatch('return_submit_success', ['return' => $return]);

                return $this->getResponse()->setRedirect($successUrl);
            } catch (\Exception $e) {
                $errorMessage = __('We can\'t create a return right now. Please try again later.');
                $urlMessage = $this->_redirect->error($this->_url->getUrl('magento_rma/returns/history'));

                $this->messageManager->addErrorMessage($errorMessage);
                $this->logger->critical($e->getMessage());

                return $this->getResponse()->setRedirect($urlMessage);
            }
        }

        return $this->_redirect('sales/order/history');
    }

    private function buildRma(Order $order, array $post): RmaInterface
    {
        $rmaModel = $this->rmaModelFactory->create();

        $rmaModel->setData([
            'status' => Status::STATE_PENDING,
            'date_requested' => $this->dateTime->gmtDate(),
            'order_id' => $order->getId(),
            'order_increment_id' => $order->getIncrementId(),
            'store_id' => $order->getStoreId(),
            'customer_id' => $order->getCustomerId(),
            'order_date' => $order->getCreatedAt(),
            'customer_name' => $order->getCustomerName(),
            'customer_custom_email' => $post['customer_custom_email'],
        ]);

        return $rmaModel;
    }
}

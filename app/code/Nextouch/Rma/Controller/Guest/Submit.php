<?php
declare(strict_types=1);

namespace Nextouch\Rma\Controller\Guest;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Rma\Api\Data\RmaInterface;
use Magento\Rma\Helper\Data;
use Magento\Rma\Model\Rma;
use Magento\Rma\Model\Rma\Source\Status;
use Magento\Rma\Model\Rma\Status\HistoryFactory;
use Magento\Rma\Model\RmaFactory;
use Magento\Sales\Helper\Guest;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderRepository;
use Nextouch\Rma\Api\RmaRepositoryInterface;
use Psr\Log\LoggerInterface;

class Submit extends Action implements HttpPostActionInterface
{
    private RmaFactory $rmaModelFactory;
    private OrderRepository $orderRepository;
    private LoggerInterface $logger;
    private DateTime $dateTime;
    private HistoryFactory $statusHistoryFactory;
    private Data $rmaHelper;
    private Guest $salesGuestHelper;
    private RmaRepositoryInterface $rmaRepository;

    public function __construct(
        Context $context,
        RmaFactory $rmaModelFactory,
        OrderRepository $orderRepository,
        LoggerInterface $logger,
        DateTime $dateTime,
        HistoryFactory $statusHistoryFactory,
        RmaRepositoryInterface $rmaRepository,
        ?Data $rmaHelper = null,
        ?Guest $salesGuestHelper = null
    ) {
        $this->rmaModelFactory = $rmaModelFactory;
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
        $this->dateTime = $dateTime;
        $this->statusHistoryFactory = $statusHistoryFactory;
        parent::__construct($context);
        $this->rmaRepository = $rmaRepository;
        $this->rmaHelper = $rmaHelper ?: $this->_objectManager->create(Data::class);
        $this->salesGuestHelper = $salesGuestHelper ?: $this->_objectManager->create(Guest::class);
    }

    public function execute()
    {
        $result = $this->salesGuestHelper->loadValidOrder($this->_request);
        if ($result instanceof ResultInterface) {
            return $result;
        }

        $orderId = (int) $this->getRequest()->getParam('order_id');
        $post = $this->getRequest()->getPostValue();
        $redirectUrl = $this->_url->getUrl('sales/guest/view/order_id', ['order_id' => $orderId]);

        if ($post && !empty($post['items'])) {
            try {
                if (!$this->rmaHelper->canCreateRma($orderId)) {
                    $errorMessage = __('We can\'t create a return transaction for order #%1.', $orderId);
                    $this->messageManager->addErrorMessage($errorMessage);

                    return $this->resultRedirectFactory->create()->setPath($redirectUrl);
                }

                /** @var Order $order */
                $order = $this->orderRepository->get($orderId);

                /** @var $rmaModel Rma */
                $rmaModel = $this->buildRma($order, $post);
                $result = $rmaModel->saveRma($post);
                if (!$result) {
                    return $this->resultRedirectFactory->create()->setPath($redirectUrl);
                }

                $statusHistory = $this->statusHistoryFactory->create();
                $statusHistory->setRmaEntityId($result->getId());
                $statusHistory->sendNewRmaEmail();
                $statusHistory->saveSystemComment();
                if (isset($post['rma_comment']) && !empty($post['rma_comment'])) {
                    $comment = $this->statusHistoryFactory->create();
                    $comment->setRmaEntityId($result->getId());
                    $comment->saveComment($post['rma_comment'], true, false);
                }

                $successMessage = __('You submitted Return #%1.', $rmaModel->getIncrementId());
                $this->messageManager->addSuccessMessage($successMessage);

                $return = $this->rmaRepository->get((int) $rmaModel->getEntityId());

                $this->_eventManager->dispatch('return_submit_success', ['return' => $return]);

                return $this->resultRedirectFactory->create()->setPath($redirectUrl);
            } catch (\Exception $e) {
                $errorMessage = __('We can\'t create a return right now. Please try again later.');
                $this->messageManager->addErrorMessage($errorMessage);
                $this->logger->critical($e);
            }
        }

        return $this->resultRedirectFactory->create()->setPath($redirectUrl);
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

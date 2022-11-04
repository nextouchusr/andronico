<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Payment\Api\Data\PaymentAdditionalInfoInterfaceFactory;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Model\Order\ShippingAssignmentBuilder;
use Magento\Tax\Api\OrderTaxManagementInterface;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Model\ResourceModel\Order\CollectionFactory;

class OrderRepository implements OrderRepositoryInterface
{
    private CollectionFactory $collectionFactory;
    private \Magento\Sales\Api\OrderRepositoryInterface $orderRepository;
    private ShippingAssignmentBuilder $shippingAssignmentBuilder;
    private OrderExtensionFactory $orderExtensionFactory;
    private OrderTaxManagementInterface $orderTaxManagement;
    private PaymentAdditionalInfoInterfaceFactory $paymentAdditionalInfoFactory;
    private JsonSerializer $serializer;

    public function __construct(
        CollectionFactory $collectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        ShippingAssignmentBuilder $shippingAssignmentBuilder,
        OrderExtensionFactory $orderExtensionFactory,
        OrderTaxManagementInterface $orderTaxManagement,
        PaymentAdditionalInfoInterfaceFactory $paymentAdditionalInfoFactory,
        JsonSerializer $serializer
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->orderRepository = $orderRepository;
        $this->shippingAssignmentBuilder = $shippingAssignmentBuilder;
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->orderTaxManagement = $orderTaxManagement;
        $this->paymentAdditionalInfoFactory = $paymentAdditionalInfoFactory;
        $this->serializer = $serializer;
    }

    public function get(int $id): OrderInterface
    {
        /** @var OrderInterface $order */
        $order = $this->collectionFactory
            ->create()
            ->addFieldToFilter('entity_id', $id)
            ->getFirstItem();

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('The order that was requested does not exist.'));
        }

        $this->setOrderTaxDetails($order);
        $this->setShippingAssignments($order);
        $this->setPaymentAdditionalInfo($order);

        return $order;
    }

    public function getByIncrementId(string $incrementId): OrderInterface
    {
        /** @var OrderInterface $order */
        $order = $this->collectionFactory
            ->create()
            ->addFieldToFilter(OrderInterface::INCREMENT_ID, $incrementId)
            ->getFirstItem();

        if (!$order->getId()) {
            throw new NoSuchEntityException(__('The order that was requested does not exist.'));
        }

        $this->setOrderTaxDetails($order);
        $this->setShippingAssignments($order);
        $this->setPaymentAdditionalInfo($order);

        return $order;
    }

    public function save(OrderInterface $order): OrderInterface
    {
        $this->orderRepository->save($order);

        return $this->get((int) $order->getEntityId());
    }

    /**
     * @throws NoSuchEntityException
     */
    private function setOrderTaxDetails(OrderInterface $order): void
    {
        $extensionAttributes = $order->getExtensionAttributes() ?? $this->orderExtensionFactory->create();
        $orderTaxDetails = $this->orderTaxManagement->getOrderTaxDetails($order->getEntityId());
        $appliedTaxes = $orderTaxDetails->getAppliedTaxes();
        $extensionAttributes->setAppliedTaxes($appliedTaxes);

        if (!$appliedTaxes) {
            $extensionAttributes->setConvertingFromQuote(true);
        }

        $items = $orderTaxDetails->getItems();
        $extensionAttributes->setItemAppliedTaxes($items);

        $order->setExtensionAttributes($extensionAttributes);
    }

    private function setPaymentAdditionalInfo(OrderInterface $order): void
    {
        $extensionAttributes = $order->getExtensionAttributes() ?? $this->orderExtensionFactory->create();
        $paymentAdditionalInformation = [];
        $payment = $order->getPayment();

        if ($payment) {
            $paymentAdditionalInformation = $payment->getAdditionalInformation();
        }

        $objects = [];
        foreach ($paymentAdditionalInformation as $key => $value) {
            $additionalInformationObject = $this->paymentAdditionalInfoFactory->create();
            $additionalInformationObject->setKey($key);

            if (!is_string($value)) {
                $value = $this->serializer->serialize($value);
            }

            $additionalInformationObject->setValue($value);
            $objects[] = $additionalInformationObject;
        }

        $extensionAttributes->setPaymentAdditionalInfo($objects);
        $order->setExtensionAttributes($extensionAttributes);
    }

    private function setShippingAssignments(\Magento\Sales\Api\Data\OrderInterface $order): void
    {
        $extensionAttributes = $order->getExtensionAttributes() ?? $this->orderExtensionFactory->create();
        if ($extensionAttributes->getShippingAssignments() !== null) {
            return;
        }

        $shippingAssignments = $this->shippingAssignmentBuilder;
        $shippingAssignments->setOrderId($order->getEntityId());
        $extensionAttributes->setShippingAssignments($shippingAssignments->create());
        $order->setExtensionAttributes($extensionAttributes);
    }
}

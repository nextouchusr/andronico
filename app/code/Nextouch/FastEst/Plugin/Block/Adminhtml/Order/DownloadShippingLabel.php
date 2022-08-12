<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Plugin\Block\Adminhtml\Order;

use Magento\Sales\Block\Adminhtml\Order\View;
use Nextouch\FastEst\Api\DeliveryRepositoryInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;

class DownloadShippingLabel
{
    private OrderRepositoryInterface $orderRepository;
    private DeliveryRepositoryInterface $deliveryRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        DeliveryRepositoryInterface $deliveryRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->deliveryRepository = $deliveryRepository;
    }

    public function beforeSetLayout(View $subject)
    {
        $fastEstOrder = $this->orderRepository->get((int) $subject->getOrderId());
        $response = $this->deliveryRepository->getOrderLabels($fastEstOrder);

        if ($response->getStatusReturn()->isOk()) {
            $subject->addButton('download_shipping_label', [
                'label' => __('Download Shipping Label'),
                'onclick' => "setLocation('data:application/octet-stream;base64,{$response->getBase64PdfLabel()}')",
            ]);
        }
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Gls\Plugin\Shipping\Helper;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\Order\Shipment\Track;
use Magento\Shipping\Helper\Data;
use Nextouch\Gls\Helper\GlsConfig;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Sales\Api\OrderRepositoryInterface;

class GetShippingTrackingPopupUrl
{
    private OrderRepositoryInterface $orderRepository;
    private GlsConfig $config;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        GlsConfig $config
    ) {
        $this->orderRepository = $orderRepository;
        $this->config = $config;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetTrackingPopupUrlBySalesModel(Data $subject, string $result, $model): string
    {
        try {
            $order = $this->fetchOrder($model);

            if ($order && $order->isShippedByGls()) {
                return $this->config->getTrackingLink($order->getTrackNumber());
            }

            return $result;
        } catch (LocalizedException $e) {
            return $result;
        }
    }

    /**
     * @throws LocalizedException
     * @noinspection PhpCastIsUnnecessaryInspection
     */
    private function fetchOrder($model): ?OrderInterface
    {
        if ($model instanceof Order) {
            return $this->orderRepository->get((int) $model->getEntityId());
        } elseif ($model instanceof Shipment) {
            return $this->orderRepository->get((int) $model->getOrderId());
        } elseif ($model instanceof Track) {
            return $this->orderRepository->get((int) $model->getOrderId());
        }

        return null;
    }
}

<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\Wins\Service\Order\InStore\OrderAccepted;
use Nextouch\Wins\Service\Order\InStore\OrderPickedUp;
use Nextouch\Wins\Service\Order\InStore\OrderRejected;
use Nextouch\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;
use function Symfony\Component\String\u;

class UpdateInStoreOrder
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(array $data): bool
    {
        $orderId = u($data['filename'])->replace('.txt', '')->slice(4)->toString();

        try {
            $this->logger->info(__('Starting to update Wins in-store order: #%1', $orderId));

            switch (trim($data['content'])) {
                case OrderInterface::IN_STORE_ORDER_ACCEPTED:
                    $instance = ObjectManager::getInstance()->get(OrderAccepted::class);
                    $instance->execute($orderId);
                    break;
                case OrderInterface::IN_STORE_ORDER_PICKED_UP:
                    $instance = ObjectManager::getInstance()->get(OrderPickedUp::class);
                    $instance->execute($orderId);
                    break;
                case OrderInterface::IN_STORE_ORDER_REJECTED:
                case OrderInterface::IN_STORE_ORDER_NOT_PICKED_UP:
                    $instance = ObjectManager::getInstance()->get(OrderRejected::class);
                    $instance->execute($orderId);
                    break;
            }

            return true;
        } catch (\Exception $e) {
            $message = __('Failed to update in-store order status: #%1 - Error: %2', $orderId, $e->getMessage());
            $this->logger->error($message);
        } finally {
            $this->logger->info(__('Finishing to update Wins in-store order: #%1', $orderId));
        }

        return false;
    }
}

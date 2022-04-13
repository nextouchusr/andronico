<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Psr7\ResponseFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Nextouch\Findomestic\Api\NotificationProcessorInterface;
use Nextouch\Findomestic\Helper\FindomesticConfig;
use Nextouch\Findomestic\Model\Request\Installment\Notification as NotificationRequest;
use Nextouch\Findomestic\Service\Notification\ApplicationCompletedNotifier;
use Nextouch\Findomestic\Service\Notification\ApplicationDeclinedNotifier;
use Nextouch\Findomestic\Service\Notification\ApplicationPreApprovedNotifier;
use Psr\Log\LoggerInterface;

class NotificationProcessor extends AbstractBaseRestApi implements NotificationProcessorInterface
{
    private ApplicationPreApprovedNotifier $applicationPreApprovedNotifier;
    private ApplicationCompletedNotifier $applicationCompletedNotifier;
    private ApplicationDeclinedNotifier $applicationDeclinedNotifier;

    public function __construct(
        ApplicationPreApprovedNotifier $applicationPreApprovedNotifier,
        ApplicationCompletedNotifier $applicationCompletedNotifier,
        ApplicationDeclinedNotifier $applicationDeclinedNotifier,
        ResponseFactory $responseFactory,
        ClientFactory $clientFactory,
        JsonSerializer $jsonSerializer,
        FindomesticConfig $config,
        LoggerInterface $logger
    ) {
        parent::__construct(
            $responseFactory,
            $clientFactory,
            $jsonSerializer,
            $config,
            $logger
        );
        $this->applicationPreApprovedNotifier = $applicationPreApprovedNotifier;
        $this->applicationCompletedNotifier = $applicationCompletedNotifier;
        $this->applicationDeclinedNotifier = $applicationDeclinedNotifier;
    }

    /**
     * @throws LocalizedException
     */
    public function notify(string $vendorId, NotificationRequest $request): void
    {
        switch (true) {
            case $request->isApplicationPreApproved():
                $this->applicationPreApprovedNotifier->execute($request);
                break;
            case $request->isApplicationCompleted():
                $this->applicationCompletedNotifier->execute($request);
                break;
            case $request->isApplicationDeclined():
                $this->applicationDeclinedNotifier->execute($request);
                break;
            default:
                throw new LocalizedException(__('Findomestic notification not recognized'));
        }
    }
}
